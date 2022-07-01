<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountStatusTrait;
use PortlandLabs\Skyline\Site\Site;

class CompleteAccountDeletionInSkylineCommandHandler
{

    use UpdateAccountStatusTrait;

    public function __invoke(CompleteAccountDeletionInSkylineCommand $command)
    {
        $this->setStatus($command->getNeighborhood(), $command->getSiteHandle(), Site::STATUS_TERMINATED);
    }

    
}
