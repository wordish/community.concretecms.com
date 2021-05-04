<?php

namespace PortlandLabs\Hosting\Software\Command;

use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Denormalizer;
use PortlandLabs\Hosting\Software\Command\UpdateConcreteReleaseCommand;

class UpdateConcreteReleaseCommandHandler
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
     * @param UpdateConcreteReleaseCommand $command
     * @return array|object
     * @throws \Exception
     */
    public function handle($command)
    {
        $data = [
            'version' => $command->getVersion(),
            'dateReleased' => $command->getDateReleased(),
            'downloadUrl' => $command->getDownloadUrl(),
            'releaseNotes' => $command->getReleaseNotes(),
            'releaseNotesUrl' => $command->getReleaseNotesUrl(),
            'upgradeNotes' => $command->getUpgradeNotes(),
            'isPublished' => $command->isPublished(),
            'md5sum' => $command->getMd5sum(),
        ];
        $response = $this->client->updateResource('/concrete_releases/' . $command->getId(), $data);

        $project = $this->denormalizer->denormalize(json_decode($response->getBody(), true));

        return $project;
    }

    
}
