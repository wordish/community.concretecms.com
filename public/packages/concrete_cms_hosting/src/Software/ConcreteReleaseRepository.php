<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Software;

use GuzzleHttp\Exception\ClientException;
use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Query\Resource\GetConcreteReleaseQuery;

class ConcreteReleaseRepository
{

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function findOneById($releaseId)
    {
        try {
            return $this->client->getResource(new GetConcreteReleaseQuery($releaseId));
        } catch (ClientException $e) {
            return null;
        }
    }
}
