<?php

namespace PortlandLabs\Hosting\Software\Command;

use PortlandLabs\Hosting\Api\Client\Client;

class DeleteConcreteReleaseCommandHandler
{

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function handle(DeleteConcreteReleaseCommand $command)
    {
        $this->client->delete('/concrete_releases/' . $command->getReleaseId());
    }

    
}
