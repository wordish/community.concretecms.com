<?php

namespace PortlandLabs\Skyline\Command;

use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\DeleteSiteInNeighborhoodCommand;

class DeleteHostingSiteCommandHandler extends SuspendHostingSiteCommandHandler
{

    /**
     * @param DeleteHostingSiteCommand $command
     */
    public function __invoke($command)
    {
        $hostingEntry = $this->getEntryFromCommand($command);
        $this->setSuspendedStatus($hostingEntry, Site::STATUS_DELETED_REMOVAL_IMMINENT);
        $this->cancelHostingSubscriptionIfExists($hostingEntry);
        $this->dispatchNeighborhoodCommand(new DeleteSiteInNeighborhoodCommand(), $hostingEntry);
        return $hostingEntry;
    }

    
}
