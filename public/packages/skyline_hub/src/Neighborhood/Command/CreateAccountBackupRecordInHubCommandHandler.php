<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use PortlandLabs\Skyline\Entity\Backup;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountTrait;

class CreateAccountBackupRecordInHubCommandHandler
{

    use UpdateAccountTrait;

    public function __invoke(CreateAccountBackupRecordInHubCommand $command)
    {
        $site = $this->getSite($command->getNeighborhood(), $command->getSiteHandle());
        if ($site) {
            // Find any backups that are loading. Put this in one of those slots.
            foreach ($site->getBackups() as $potentiallyLoadingBackup) {
                if ($potentiallyLoadingBackup->isLoading()) {
                    $backup = $potentiallyLoadingBackup;
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
        }
    }


}
