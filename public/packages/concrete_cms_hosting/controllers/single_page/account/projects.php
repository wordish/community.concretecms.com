<?php

namespace Concrete\Package\ConcreteCmsHosting\Controller\SinglePage\Account;

use Concrete\Core\Page\Controller\AccountPageController;
use Pagerfanta\Pagerfanta;
use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Pagination\Adapter\QueryAdapter;
use PortlandLabs\Hosting\Api\Client\Query\Resource\SearchUsersProjectsQuery;
use PortlandLabs\Hosting\Project\ProjectRepository;
use PortlandLabs\Hosting\Serializer\Serializer;

class Projects extends AccountPageController
{

    public function view()
    {
    }

}
