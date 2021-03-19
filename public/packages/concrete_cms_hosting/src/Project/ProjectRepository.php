<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Project;

use GuzzleHttp\Exception\ClientException;
use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Query\Resource\GetProjectQuery;

class ProjectRepository
{

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function findOneById($projectId)
    {
        try {
            return $this->client->getResource(new GetProjectQuery($projectId));
        } catch (ClientException $e) {
            return null;
        }
    }
}
