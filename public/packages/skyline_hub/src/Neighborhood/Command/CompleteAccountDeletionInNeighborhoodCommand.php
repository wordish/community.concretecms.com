<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Concrete\Core\Foundation\Command\Command;

class CompleteAccountDeletionInNeighborhoodCommand extends Command
{

    /**
     * @var string
     */
    protected $neighborhood;

    /**
     * @var string
     */
    protected $siteHandle;

    /**
     * @return string
     */
    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    /**
     * @param string $neighborhood
     */
    public function setNeighborhood(string $neighborhood): void
    {
        $this->neighborhood = $neighborhood;
    }

    /**
     * @return string
     */
    public function getSiteHandle(): string
    {
        return $this->siteHandle;
    }

    /**
     * @param string $siteHandle
     */
    public function setSiteHandle(string $siteHandle): void
    {
        $this->siteHandle = $siteHandle;
    }






}
