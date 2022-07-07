<?php

namespace PortlandLabs\Skyline\Command;

use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\ReinstateSiteInNeighborhoodCommand;

class ReinstateHostingSiteCommandHandler extends SuspendHostingSiteCommandHandler
{

    /**
     * @param ReinstateHostingSiteCommand $command
     */
    public function __invoke($command)
    {
        $hostingEntry = $this->getEntryFromCommand($command);
        $this->setSuspendedStatus($hostingEntry, Site::STATUS_REINSTATING);
        $this->dispatchNeighborhoodCommand(new ReinstateSiteInNeighborhoodCommand(), $hostingEntry);
        return $hostingEntry;
    }

    
}
