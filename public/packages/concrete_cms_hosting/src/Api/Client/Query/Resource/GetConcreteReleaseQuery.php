<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query\Resource;

use PortlandLabs\Hosting\Api\Client\Query\QueryInterface;

class GetConcreteReleaseQuery implements QueryInterface
{

    /**
     * @var int
     */
    protected $releaseId;

    public function __construct($releaseId)
    {
        $this->releaseId = $releaseId;
    }

    public function getEndpoint(): string
    {
        return '/concrete_releases/' . (string) $this->releaseId;
    }


}
