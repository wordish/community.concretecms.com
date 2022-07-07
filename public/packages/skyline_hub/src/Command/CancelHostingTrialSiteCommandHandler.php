<?php

namespace PortlandLabs\Skyline\Command;

use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\SuspendSiteInNeighborhoodCommand;

class CancelHostingTrialSiteCommandHandler extends SuspendHostingSiteCommandHandler
{

    /**
     * @param CancelHostingTrialSiteCommand $command
     */
    public function __invoke($command)
    {
        $hostingEntry = $this->getEntryFromCommand($command);
        $this->setSuspendedStatus($hostingEntry, Site::STATUS_SUSPENDED_TRIAL_CANCELLED);
        $this->cancelHostingSubscriptionIfExists($hostingEntry);
        $this->dispatchNeighborhoodCommand(new SuspendSiteInNeighborhoodCommand(), $hostingEntry);
        return $hostingEntry;
    }

}

