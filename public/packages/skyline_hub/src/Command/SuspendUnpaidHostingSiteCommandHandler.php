<?php

namespace PortlandLabs\Skyline\Command;

use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\SuspendSiteInNeighborhoodCommand;

class SuspendUnpaidHostingSiteCommandHandler extends SuspendHostingSiteCommandHandler
{

    /**
     * @param SuspendUnpaidHostingSiteCommand $command
     */
    public function __invoke($command)
    {
        $hostingEntry = $this->getEntryFromCommand($command);
        $this->setSuspendedStatus($hostingEntry, Site::STATUS_SUSPENDED_UNPAID);
        $this->dispatchNeighborhoodCommand(new SuspendSiteInNeighborhoodCommand(), $hostingEntry);
        return $hostingEntry;
    }


}
