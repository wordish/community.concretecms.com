<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountTrait;

class CompleteAccountDeletionInSkylineCommandHandler
{

    use UpdateAccountTrait;

    public function __invoke(CompleteAccountDeletionInSkylineCommand $command)
    {
        $site = $this->getSite($command->getNeighborhood(), $command->getSiteHandle());
        $entityManager = app(EntityManager::class);
        if ($site) {
            $entityManager->remove($site);
            $entityManager->flush();
        }
    }


}
