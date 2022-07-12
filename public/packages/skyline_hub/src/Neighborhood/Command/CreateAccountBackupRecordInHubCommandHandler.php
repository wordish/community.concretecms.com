<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Concrete\Core\Notification\Events\MercureService;
use PortlandLabs\Skyline\Entity\Backup;
use PortlandLabs\Skyline\Events\ServerEvent\BackupCreated;
use PortlandLabs\Skyline\Events\ServerEvent\BackupUpdated;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountTrait;

class CreateAccountBackupRecordInHubCommandHandler
{

    use UpdateAccountTrait;

    /**
     * @var MercureService
     */
    protected $mercureService;

    public function __construct(MercureService $mercureService)
    {
        $this->mercureService = $mercureService;
    }

    public function __invoke(CreateAccountBackupRecordInHubCommand $command)
    {
        $this->clearEntityManager();
        $site = $this->getSite($command->getNeighborhood(), $command->getSiteHandle());
        if ($site) {
            $newBackup = true;
            // Find any backups that are loading. Put this in one of those slots.
            foreach ($site->getBackups() as $potentiallyLoadingBackup) {
                if ($potentiallyLoadingBackup->isLoading()) {
                    $backup = $potentiallyLoadingBackup;
                    $newBackup = false;
                }
            }
            if (!isset($backup)) {
                $backup = new Backup();
            }
            $backup->setFilename($command->getFile());
            $backup->setSite($site);
            $backup->setSize($command->getSize());
            $backup->setBackupFileID($command->getBackupFileID());
            $em = $this->getEntityManager();
            $em->persist($backup);
            $em->flush();

            if ($newBackup) {
                $this->mercureService->sendUpdate(new BackupCreated($backup));
            } else {
                $this->mercureService->sendUpdate(new BackupUpdated($backup));
            }
        }
    }


}
