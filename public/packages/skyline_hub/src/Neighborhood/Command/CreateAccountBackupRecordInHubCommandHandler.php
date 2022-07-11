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
            $backup = new Backup();
            $backup->setFilename($command->getFile());
            $backup->setSite($site);
            $em = $this->getEntityManager();
            $em->persist($backup);
            $em->flush();
        }
    }


}
