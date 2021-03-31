<?php

namespace PortlandLabs\Hosting\Project\Command;

class CreateLagoonProjectCommandHandler extends CreateProjectCommandHandler
{

    /**
     * @param CreateLagoonProjectCommand $command
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
        $response = $this->client->createResource('/api/lagoon_projects', $data);
        $project = $this->denormalizer->denormalize(json_decode($response->getBody(), true));

        return $project;
    }

    
}
