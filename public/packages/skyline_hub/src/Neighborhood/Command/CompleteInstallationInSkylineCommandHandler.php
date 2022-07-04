<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountTrait;
use PortlandLabs\Skyline\Site\Site;

class CompleteInstallationInSkylineCommandHandler
{

    use UpdateAccountTrait;

    public function __invoke(CompleteInstallationInSkylineCommand $command)
    {
        $this->setStatus(
            $command->getNeighborhood(),
            $command->getSiteHandle(),
            ['hosting_site_status' => Site::STATUS_ACTIVE, 'hosting_site_password' => null]
        );
    }


}
