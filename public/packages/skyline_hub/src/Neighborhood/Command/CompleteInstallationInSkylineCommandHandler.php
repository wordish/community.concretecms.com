<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountTrait;

class CompleteInstallationInSkylineCommandHandler
{

    use UpdateAccountTrait;

    public function __invoke(CompleteInstallationInSkylineCommand $command)
    {
        $this->setStatus(
            $command->getNeighborhood(),
            $command->getSiteHandle(),
            function(Site $site) {
                $site->setStatus(Site::STATUS_ACTIVE);
                $site->setAdminPassword(null);
                return $site;
            }
        );
    }


}
