<?php

namespace PortlandLabs\Skyline\Neighborhood;

class NeighborhoodSelector
{

    protected $neighborhoods = ['irvington'];

    /**
     * Selects a neighborhood for a new site entry.
     * @TODO - make this have some kind of logic, add new neighborhoods, add new arguments to this function
     * that help determine which neighborhood to use.
     */
    public function chooseNeighborhoodForNewSite(): string
    {
        return $this->neighborhoods[array_rand($this->neighborhoods)];
    }


}
