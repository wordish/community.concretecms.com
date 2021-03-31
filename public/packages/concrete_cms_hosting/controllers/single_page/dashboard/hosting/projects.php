<?php

namespace Concrete\Package\ConcreteCmsHosting\Controller\SinglePage\Dashboard\Hosting;

use Concrete\Core\Board\Command\CreateBoardCommand;
use Concrete\Core\Entity\Board\Template;
use Concrete\Core\Entity\Search\Query;
use Concrete\Core\Filesystem\Element;
use Concrete\Core\Filesystem\ElementManager;
use Concrete\Core\Http\Request;
use Concrete\Core\Navigation\Breadcrumb\Dashboard\DashboardBreadcrumbFactory;
use Concrete\Core\Navigation\Item\Item;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Search\Field\Field\KeywordsField;
use Concrete\Core\Search\Query\Modifier\AutoSortColumnRequestModifier;
use Concrete\Core\Search\Query\Modifier\ItemsPerPageRequestModifier;
use Concrete\Core\Search\Query\QueryFactory;
use Concrete\Core\Search\Query\QueryModifier;
use Concrete\Core\Search\Result\Result;
use Concrete\Core\Search\Result\ResultFactory;
use Concrete\Core\Utility\Service\Validation\Strings;
use Concrete\Core\Validation\SanitizeService;
use PortlandLabs\Hosting\Project\Command\CreateLagoonProjectCommand;
use PortlandLabs\Hosting\Project\Command\CreateProjectCommand;
use PortlandLabs\Hosting\Project\Command\DeleteProjectCommand;
use PortlandLabs\Hosting\Project\Command\UpdateLagoonProjectCommand;
use PortlandLabs\Hosting\Project\Command\UpdateProjectCommand;
use PortlandLabs\Hosting\Project\Project;
use PortlandLabs\Hosting\Project\ProjectRepository;
use PortlandLabs\Hosting\Project\Search\SearchProvider;

class Projects extends DashboardPageController
{

    /**
     * @var Element
     */
    protected $headerMenu;

    /**
     * @var Element
     */
    protected $headerSearch;

    /**
     * @return SearchProvider
     */
    protected function getSearchProvider()
    {
        return $this->app->make(SearchProvider::class);
    }

    /**
     * @return QueryFactory
     */
    protected function getQueryFactory()
    {
        return $this->app->make(QueryFactory::class);
    }

    protected function getHeaderMenu()
    {
        if (!isset($this->headerMenu)) {
            $this->headerMenu = $this->app->make(ElementManager::class)
                ->get('dashboard/hosting/projects/search/menu', 'concrete_cms_hosting');
        }

        return $this->headerMenu;
    }

    protected function getSearchKeywordsField()
    {
        $keywords = null;

        if ($this->request->query->has('keywords')) {
            $keywords = $this->request->query->get('keywords');
        }

        return new KeywordsField($keywords);
    }


    protected function getHeaderSearch()
    {
        if (!isset($this->headerSearch)) {
            $this->headerSearch = $this->app->make(ElementManager::class)
                ->get('dashboard/hosting/projects/search/search', 'concrete_cms_hosting');
        }

        return $this->headerSearch;
    }

    public function view()
    {
        $query = $this->getQueryFactory()->createQuery($this->getSearchProvider(), [
            $this->getSearchKeywordsField()
        ]);
        $result = $this->createSearchResult($query);
        $this->renderSearchResult($result);
    }

    /**
     * @param Query $query
     * @return Result
     */
    protected function createSearchResult(Query $query)
    {
        $provider = $this->getSearchProvider();
        $resultFactory = $this->app->make(ResultFactory::class);
        $queryModifier = $this->app->make(QueryModifier::class);
        $queryModifier->addModifier(new AutoSortColumnRequestModifier($provider, $this->request, Request::METHOD_GET));
        $queryModifier->addModifier(new ItemsPerPageRequestModifier($provider, $this->request, Request::METHOD_GET));
        $query = $queryModifier->process($query);
        return $resultFactory->createFromQuery($provider, $query);
    }

    /**
     * @param Result $result
     */
    protected function renderSearchResult(Result $result)
    {
        $headerMenu = $this->getHeaderMenu();
        $headerSearch = $this->getHeaderSearch();
        $headerMenu->getElementController()->setQuery($result->getQuery());
        $headerSearch->getElementController()->setQuery($result->getQuery());
        $this->set('result', $result);
        $this->set('headerMenu', $headerMenu);
        $this->set('headerSearch', $headerSearch);
        $this->setThemeViewTemplate('full.php');
    }

    protected function validateProjectRequest()
    {
        $security = new SanitizeService();
        $strings = $this->app->make(Strings::class);
        $name = $security->sanitizeString($this->post('name'));

        if (!$strings->notempty($name)) {
            $this->error->add(t('You must specify a valid name for your project.'));
        }

        return [$name];
    }

    public function create_project()
    {
        list($name) = $this->validateProjectRequest();
        if (!$this->token->validate('create_project')) {
            $this->error->add(t($this->token->getErrorMessage()));
        }
        if (!$this->error->has()) {

            if ($this->request->request->get('projectType') == 'lagoon') {
                $command = new CreateLagoonProjectCommand();
                $command->setLagoonId($this->request->request->get('lagoonId'));
            } else {
                $command = new CreateProjectCommand();
            }
            $command->setName($name);
            $command->setUserId($this->request->request->get('userId'));

            $project = $this->executeCommand($command);
            $this->flash('success', t('Project created successfully.'));

            return $this->buildRedirect(['/dashboard/hosting/projects', 'view_details', $project->getId()]);
        }
        $this->view();
    }

    public function view_details($projectId = null)
    {
        $project = $this->getProject($projectId);
        $this->setCurrentProject($project);
        $this->render('/dashboard/hosting/projects/details');
    }

    public function edit($projectId = null)
    {
        $project = $this->getProject($projectId);
        $this->setCurrentProject($project);
        $this->render('/dashboard/hosting/projects/form');
    }

    public function update_project($projectId = null)
    {
        $project = $this->getProject($projectId);
        list($name) = $this->validateProjectRequest();
        $this->setCurrentProject($project);
        if (!$this->token->validate('update_project')) {
            $this->error->add(t($this->token->getErrorMessage()));
        }
        if (!$this->error->has()) {

            if ($this->request->request->get('projectType') == 'lagoon') {
                $command = new UpdateLagoonProjectCommand($project->getId());
                $command->setLagoonId($this->request->request->get('lagoonId'));
            } else {
                $command = new UpdateProjectCommand($project->getId());
            }
            $command->setName($name);
            $command->setUserId($this->request->request->get('userId'));

            $project = $this->executeCommand($command);
            $this->flash('success', t('Project updated successfully.'));

            return $this->buildRedirect(['/dashboard/hosting/projects', 'view_details', $project->getId()]);
        }
        $this->render('/dashboard/hosting/projects/form');
    }

    public function add_project($projectType = null)
    {
        $this->set('projectType', $projectType);
        $this->render('/dashboard/hosting/projects/form');
    }

    public function getProject($projectId)
    {
        $project = null;
        if ($projectId) {
            $project = $this->app->make(ProjectRepository::class)
                ->findOneById($projectId);
        }

        if (!$project) {
            $this->flash('error', t('Invalid project ID'));
            return $this->buildRedirect(['/dashboard/hosting/projects']);
        }

        return $project;
    }

    protected function setCurrentProject(Project $project)
    {
        $this->set('project', $project);
        $breadcrumb = $this->app->make(DashboardBreadcrumbFactory::class)->getBreadcrumb($this->getPageObject());
        $breadcrumb->add(new Item('', $project->getName()));
        $this->setBreadcrumb($breadcrumb);
    }

    public function delete_project()
    {
        $projectId = (int) $this->request->request->get('id');
        $project = $this->app->make(ProjectRepository::class)
            ->findOneById($projectId);
        if ($project === null) {
            $this->flash('error', t('The project specified does not exist.'));
            return $this->buildRedirect($this->action());
        }
        if (!$this->token->validate('delete_project')) {
            $this->error->add($this->token->getErrorMessage());
        }
        if (!$this->error->has()) {
            $command = new DeleteProjectCommand($projectId);
            $this->executeCommand($command);
            $this->flash('success', t('Project removed successfully.'));

            return $this->buildRedirect($this->action());
        }
        return $this->view_details($projectId->getId());
    }


}
