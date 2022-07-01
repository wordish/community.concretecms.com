<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountStatusTrait;
use PortlandLabs\Skyline\Site\Site;

class CompleteInstallationInSkylineCommandHandler
{

    use UpdateAccountStatusTrait;

    public function __invoke(CompleteInstallationInSkylineCommand $command)
    {
        $this->setStatus($command->getNeighborhood(), $command->getSiteHandle(), Site::STATUS_INSTALLED);
    }

    
}
