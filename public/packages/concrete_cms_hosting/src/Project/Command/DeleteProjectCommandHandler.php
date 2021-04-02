<?php

namespace PortlandLabs\Hosting\Project\Command;

use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Project\Command\DeleteProjectCommand;

class DeleteProjectCommandHandler
{

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function handle(DeleteProjectCommand $command)
    {
        $this->client->delete('/projects/' . $command->getProjectId());
    }

    
}
