<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query\Resource;

use PortlandLabs\Hosting\Api\Client\Query\AbstractQuery;

class ProjectQuery extends AbstractQuery
{

    protected $searchKeywords = null;

    public function getEndpoint(): string
    {
        return '/api/projects';
    }

    /**
     * @param null $searchKeywords
     */
    public function setSearchKeywords($searchKeywords): void
    {
        $this->queryParameters['name'] = $searchKeywords;
    }


}
