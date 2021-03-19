<?php

namespace Concrete\Package\ConcreteCmsHosting\Controller\SinglePage\Account;

use Concrete\Core\Page\Controller\AccountPageController;
use Pagerfanta\Pagerfanta;
use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Pagination\Adapter\QueryAdapter;
use PortlandLabs\Hosting\Api\Client\Query\Resource\SearchUsersProjectsQuery;
use PortlandLabs\Hosting\Project\ProjectRepository;

class Projects extends AccountPageController
{

    public function view()
    {
        $client = $this->app->make(Client::class);
        $query = new SearchUsersProjectsQuery($this->get('profile')->getUserID());
        if ($this->request->query->has('page')) {
            $query->setCurrentPage($this->request->query->get('page'));
        }
        $queryAdapter = new QueryAdapter($client, $query);
        $pagination = new Pagerfanta($queryAdapter);
        if ($this->request->query->has('page')) {
            $pagination->setCurrentPage($this->request->query->get('page'));
        }
        $paginationView = $this->app->make('manager/view/pagination')->driver('application');

        $this->set('pagination', $pagination);
        $this->set('paginationView', $paginationView);
    }

    public function panel($projectId = null)
    {
        $project = null;
        if ($projectId) {
            $project = $this->app->make(ProjectRepository::class)
                ->findOneById($projectId);
        }

        if (!$project) {
            $this->flash('error', t('Invalid project ID'));
            return $this->buildRedirect(['/account/projects']);
        }
        $this->render('/account/projects/panel');
    }
}
