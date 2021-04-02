<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query\Resource;

use PortlandLabs\Hosting\Api\Client\Query\AbstractSearchQuery;

class SearchProjectsQuery extends AbstractSearchQuery
{

    protected $searchKeywords = null;

    public function getEndpoint(): string
    {
        return '/projects';
    }

    /**
     * @param null $searchKeywords
     */
    public function setSearchKeywords($searchKeywords): void
    {
        $this->queryParameters['name'] = $searchKeywords;
    }


}
