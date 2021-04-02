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
        $request = [
            'name' => $command->getName(),
            'gitUrl' => $command->gitUrl,
            'authorizedAdmins' => $command->adminUsers,
            'authorizedUsers' => $command->users,
            'productionBranch' => $command->productionBranch,
            'stageBranches' => $command->stageBranches,
        ];

        $response = $this->client->createResource('/projects', $request);
        $project = $this->denormalizer->denormalize(json_decode($response->getBody(), true));

        return $project;
    }

    
}
