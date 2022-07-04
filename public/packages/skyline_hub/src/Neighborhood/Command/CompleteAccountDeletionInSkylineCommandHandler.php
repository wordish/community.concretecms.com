<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Concrete\Core\Express\ObjectManager;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountTrait;

class CompleteAccountDeletionInSkylineCommandHandler
{

    use UpdateAccountTrait;

    public function __invoke(CompleteAccountDeletionInSkylineCommand $command)
    {
        $sites = $this->getSites($command->getNeighborhood(), $command->getSiteHandle());
        $objectManager = app(ObjectManager::class);
        foreach ($sites as $site) {
            $objectManager->deleteEntry($site);
        }
    }


}
