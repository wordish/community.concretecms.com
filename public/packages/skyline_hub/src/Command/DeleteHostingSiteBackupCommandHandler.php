<?php

namespace PortlandLabs\Skyline\Command;

use Aws\S3\S3Client;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Backup;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\ReinstateSiteInNeighborhoodCommand;

class DeleteHostingSiteBackupCommandHandler extends SuspendHostingSiteCommandHandler
{

    /**
     * @var S3Client
     */
    protected $client;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(S3Client $client, EntityManager $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }


    /**
     * @param DeleteHostingSiteBackupCommand $command
     */
    public function __invoke($command)
    {
        $backup = $this->entityManager->find(Backup::class, $command->getBackupId());
        if ($backup->getBackupFileID()) {
            $this->client->deleteObject(['Bucket' => $_ENV['AWS_BUCKET_BACKUPS'], 'Key' => $backup->getBackupFileID()]);
        }
        $this->entityManager->remove($backup);
        $this->entityManager->flush();
    }

    
}
