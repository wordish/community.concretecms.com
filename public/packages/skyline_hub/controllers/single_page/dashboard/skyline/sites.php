<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Dashboard\Skyline;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Entity\Search\Query;
use Concrete\Core\Filesystem\Element;
use Concrete\Core\Filesystem\ElementManager;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Page;
use Concrete\Core\Permission\Checker;
use Concrete\Core\Search\Field\Field\KeywordsField;
use Concrete\Core\Search\Query\Modifier\AutoSortColumnRequestModifier;
use Concrete\Core\Search\Query\Modifier\ItemsPerPageRequestModifier;
use Concrete\Core\Search\Query\QueryModifier;
use Concrete\Core\Search\Result\Result;
use Concrete\Core\Search\Result\ResultFactory;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Search\Query\QueryFactory;
use PortlandLabs\Skyline\Entity\Site as HostingSiteEntity;
use PortlandLabs\Skyline\Entity\Search\SavedSiteSearch;
use PortlandLabs\Skyline\Search\Site\SearchProvider;

class Sites extends DashboardPageController
{
    /**
     * @var Element
     */
    protected $headerMenu;

    /**
     * @var Element
     */
    protected $headerSearch;

    /** @var ResponseFactory */
    protected $responseFactory;
    /** @var Request */
    protected $request;

    public function on_start()
    {
        parent::on_start();

        $this->responseFactory = $this->app->make(ResponseFactory::class);
        $this->request = $this->app->make(Request::class);
    }

    /**
     * @noinspection PhpInconsistentReturnPointsInspection
     * @param HostingSiteEntity $entry
     * @return Response
     */
    private function save($entry)
    {
        $data = $this->request->request->all();

        if ($this->validate($data)) {
            $entry->setName($data["name"]);
            $entry->setHandle($data["handle"]);
            $entry->setSubscriptionId($data["subscriptionId"]);
            $entry->setSubscriptionStatus($data["subscriptionStatus"]);
            $entry->setNeighborhood($data["neighborhood"]);
            $entry->setAdminPassword($data["adminPassword"]);
            $entry->setStatus($data["status"]);
            $entry->setSuspendedTimestamp($data["suspendedTimestamp"]);

            $this->entityManager->persist($entry);
            $this->entityManager->flush();

            $this->flash('success', t('Entry saved.'));

            return $this->buildRedirect(['/dashboard/skyline/sites', 'view_details', $entry->getId()]);
        }
    }

    private function setDefaults($entry = null)
    {
        $this->set("entry", $entry);
        $this->render("/dashboard/skyline/sites/edit");
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     * @param array $data
     * @return bool
     */
    public function validate($data = null)
    {
        return !$this->error->has();
    }

    /**
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function add()
    {
        $this->setDefaults(new HostingSiteEntity());
    }

    public function submit()
    {
        $entry = new HostingSiteEntity();

        if ($this->token->validate("save_skyline_site_entity")) {
            return $this->save($entry);
        }

        $this->setDefaults($entry);
    }

    /**
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function edit($id = null)
    {
        /** @var HostingSiteEntity $entry */
        $entry = $this->entityManager->getRepository(HostingSiteEntity::class)->findOneBy(
            [
                "id" => $id
            ]
        );

        if ($entry instanceof HostingSiteEntity) {
            if ($this->token->validate("save_skyline_site_entity")) {
                return $this->save($entry);
            }

            $this->setDefaults($entry);
        } else {
            $this->responseFactory->notFound(null)->send();
            $this->app->shutdown();
        }
    }

    public function view_details($id = null)
    {
        /** @var HostingSiteEntity $entry */
        $entry = $this->entityManager->getRepository(HostingSiteEntity::class)->findOneBy(
            [
                "id" => $id
            ]
        );

        if ($entry instanceof HostingSiteEntity) {
            $this->set('entry', $entry);
            $this->set('allowDelete', (new Checker())->canUpdateSkylineSiteEntries());
            $this->set('backURL', \URL::to('/dashboard/skyline/sites'));
            $this->set('editURL', \URL::to('/dashboard/skyline/sites', 'edit', $entry->getID()));
            $this->render('/dashboard/skyline/sites/details');
        } else {
            throw new \Exception(t('Invalid id.'));
        }
    }

    /**
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function remove($id = null)
    {
        /** @var HostingSiteEntity $entry */
        $entry = $this->entityManager->getRepository(HostingSiteEntity::class)->findOneBy(
            [
                "id" => $id
            ]
        );

        if (!$this->token->validate("remove")) {
            $this->error->add($this->token->getErrorMessage());
        }

        if (!$entry instanceof HostingSiteEntity) {
            $this->error->add(t("Invalid hosting site."));
        }

        if (!$this->error->has()) {
            $this->entityManager->remove($entry);
            $this->entityManager->flush();

            $this->flash('success', t('Site removed.'));
            return $this->buildRedirect(['/dashboard/skyline/sites']);
        }

    }

    public function view()
    {
        $query = $this->getQueryFactory()->createQuery(
            $this->getSearchProvider(),
            [
                $this->getSearchKeywordsField()
            ]
        );

        $result = $this->createSearchResult($query);

        $this->renderSearchResult($result);

        $this->headerSearch->getElementController()->setQuery(null);
    }

    protected function getHeaderMenu()
    {
        if (!isset($this->headerMenu)) {
            /** @var ElementManager $elementManager */
            $elementManager = $this->app->make(ElementManager::class);
            $this->headerMenu = $elementManager->get('site/header/menu', Page::getCurrentPage(), [], 'skyline_hub');
        }

        return $this->headerMenu;
    }

    protected function getHeaderSearch()
    {
        if (!isset($this->headerSearch)) {
            /** @var ElementManager $elementManager */
            $elementManager = $this->app->make(ElementManager::class);
            $this->headerSearch = $elementManager->get('site/header/search', Page::getCurrentPage(), [], 'skyline_hub');
        }

        return $this->headerSearch;
    }

    /**
     * @return QueryFactory
     */
    protected function getQueryFactory()
    {
        return $this->app->make(QueryFactory::class);
    }

    /**
     * @return SearchProvider
     */
    protected function getSearchProvider()
    {
        return $this->app->make(SearchProvider::class);
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

        $exportArgs = [$this->getPageObject()->getCollectionPath(), 'csv_export'];
        if ($this->getAction() == 'advanced_search') {
            $exportArgs[] = 'advanced_search';
        }
        $exportURL = $this->app->make('url/resolver/path')->resolve($exportArgs);
        $query = \Concrete\Core\Url\Url::createFromServer($_SERVER)->getQuery();
        $exportURL = $exportURL->setQuery($query);
        $headerMenu->getElementController()->setExportURL($exportURL);

        $this->set('result', $result);
        $this->set('headerMenu', $headerMenu);
        $this->set('headerSearch', $headerSearch);

        $this->setThemeViewTemplate('full.php');
    }

    /**
     * @param Query $query
     * @return Result
     */
    protected function createSearchResult(Query $query)
    {
        $provider = $this->app->make(SearchProvider::class);
        $resultFactory = $this->app->make(ResultFactory::class);
        $queryModifier = $this->app->make(QueryModifier::class);

        $queryModifier->addModifier(new AutoSortColumnRequestModifier($provider, $this->request, Request::METHOD_GET));
        $queryModifier->addModifier(new ItemsPerPageRequestModifier($provider, $this->request, Request::METHOD_GET));
        $query = $queryModifier->process($query);

        return $resultFactory->createFromQuery($provider, $query);
    }

    protected function getSearchKeywordsField()
    {
        $keywords = null;

        if ($this->request->query->has('keywords')) {
            $keywords = $this->request->query->get('keywords');
        }

        return new KeywordsField($keywords);
    }

    public function advanced_search()
    {
        $query = $this->getQueryFactory()->createFromAdvancedSearchRequest(
            $this->getSearchProvider(),
            $this->request,
            Request::METHOD_GET
        );

        $result = $this->createSearchResult($query);

        $this->renderSearchResult($result);
    }

    public function preset($presetID = null)
    {
        if ($presetID) {
            $preset = $this->entityManager->find(SavedSiteSearch::class, $presetID);

            if ($preset) {
                $query = $this->getQueryFactory()->createFromSavedSearch($preset);
                $result = $this->createSearchResult($query);
                $this->renderSearchResult($result);

                return;
            }
        }

        $this->view();
    }

}
