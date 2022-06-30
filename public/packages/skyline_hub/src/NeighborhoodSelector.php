<?php

namespace PortlandLabs\Skyline;

use PortlandLabs\Skyline\Neighborhood\Neighborhood;

class NeighborhoodSelector
{

    /**
     * @var NeighborhoodListFactory
     */
    protected $neighborhoodListFactory;

    public function __construct(NeighborhoodListFactory $neighborhoodListFactory)
    {
        $this->neighborhoodListFactory = $neighborhoodListFactory;
    }

    /**
     * Selects a neighborhood for a new site entry.
     */
    public function chooseNeighborhoodForNewSite(): Neighborhood
    {
        $neighborhoods = $this->neighborhoodListFactory->createList()->getNeighborhoods();
        shuffle($neighborhoods);
        return $neighborhoods[0];
    }


}
