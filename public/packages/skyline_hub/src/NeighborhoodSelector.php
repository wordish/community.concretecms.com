<?php

namespace PortlandLabs\Skyline;

use PortlandLabs\Skyline\Neighborhood\Neighborhood;

class NeighborhoodSelector
{

    /**
     * @var NeighborhoodList
     */
    protected $neighborhoodList;

    public function __construct(NeighborhoodList $neighborhoodList)
    {
        $this->neighborhoodList = $neighborhoodList;
    }

    /**
     * Selects a neighborhood for a new site entry.
     */
    public function chooseNeighborhoodForNewSite(): Neighborhood
    {
        $neighborhoods = $this->neighborhoodList->getNeighborhoods();
        shuffle($neighborhoods);
        return $neighborhoods[0];
    }


}
