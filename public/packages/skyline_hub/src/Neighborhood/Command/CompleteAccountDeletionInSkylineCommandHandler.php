<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountTrait;
use PortlandLabs\Skyline\Site\Site;

class CompleteAccountDeletionInSkylineCommandHandler
{

    use UpdateAccountTrait;

    public function __invoke(CompleteAccountDeletionInSkylineCommand $command)
    {
        $this->setStatus(
            $command->getNeighborhood(),
            $command->getSiteHandle(),
            [
                'hosting_site_status' => Site::STATUS_TERMINATED
            ]
        );
    }


}
