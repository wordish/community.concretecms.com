<?php

namespace PortlandLabs\Hosting\Project\Command;

use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Denormalizer;

class UpdateProjectCommandHandler
{

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Denormalizer
     */
    protected $denormalizer;

    public function __construct(Client $client, Denormalizer $denormalizer)
    {
        $this->client = $client;
        $this->denormalizer = $denormalizer;
    }

    /**
     * @param UpdateProjectCommand $command
     * @return array|object
     * @throws \Exception
     */
    public function handle($command)
    {
        $data = [
            'name' => $command->getName(),
            'userId' => $command->getUserId(),
            'dateCreated' => time(),
        ];
        $response = $this->client->updateResource('/projects/' . $command->getId(), $data);

        $project = $this->denormalizer->denormalize(json_decode($response->getBody(), true));

        return $project;
    }

    
}
