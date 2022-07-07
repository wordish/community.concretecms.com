<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\UpdateAccountTrait;

class CompleteInstallationInHubCommandHandler
{

    use UpdateAccountTrait;

    public function __invoke(CompleteInstallationInHubCommand $command)
    {
        $this->setStatus(
            $command->getNeighborhood(),
            $command->getSiteHandle(),
            function(Site $site) use ($command) {
                $site->setStatus(Site::STATUS_ACTIVE);
                $site->setAdminPassword(null);
                if ($command->getBytesUsed() != null) {
                    $site->setBytesUsed((int) $command->getBytesUsed());
                }
                return $site;
            }
        );
    }


}
