<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query\Resource;

use PortlandLabs\Hosting\Api\Client\Query\AbstractSearchQuery;

class SearchUsersProjectsQuery extends AbstractSearchQuery
{

    /**
     * SearchUsersProjectsQuery constructor.
     * @param null $userId
     */
    public function __construct($userId)
    {
        $this->setItemsPerPage(10);
        $this->queryParameters['userId'] = $userId;
    }

    public function getEndpoint(): string
    {
        return '/api/projects';
    }


}
