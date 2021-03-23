<?php

namespace PortlandLabs\Hosting\Project\Command;

use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Denormalizer;

class UpdateLagoonProjectCommandHandler extends UpdateProjectCommandHandler
{
    /**
     * @param UpdateLagoonProjectCommand $command
     * @return array|object
     * @throws \Exception
     */
    public function handle($command)
    {
        $data = [
            'name' => $command->getName(),
            'userId' => $command->getUserId(),
            'dateCreated' => time(),
            'lagoonId' => $command->getLagoonId(),
        ];
        $response = $this->client->updateResource('/api/projects/' . $command->getId(), $data);

        $project = $this->denormalizer->denormalize(json_decode($response->getBody(), true));

        return $project;
    }

    
}
