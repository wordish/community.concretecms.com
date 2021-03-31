<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query\Resource;

use PortlandLabs\Hosting\Api\Client\Query\QueryInterface;

class GetProjectQuery implements QueryInterface
{

    /**
     * @var int
     */
    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    public function getEndpoint(): string
    {
        return '/api/projects/' . (string) $this->projectId;
    }


}
