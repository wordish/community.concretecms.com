<?php

namespace PortlandLabs\Skyline;

use PortlandLabs\Skyline\Neighborhood\Neighborhood;

class NeighborhoodListFactory
{

    public function createList(): NeighborhoodList
    {
        if ($_ENV['SKYLINE_NEIGHBORHOOD_LIST_ENVIRONMENT'] === 'dev') {
            return (new NeighborhoodList)
                ->addNeighborhood(new Neighborhood('irvington', 'irvington.local:8080', false));

        }
    }
}
