<?php

namespace PortlandLabs\Skyline;

use PortlandLabs\Skyline\Neighborhood\Neighborhood;

class NeighborhoodList
{

    protected $neighborhoods = [];

    public function addNeighborhood(Neighborhood $neighborhood)
    {
        $this->neighborhoods[] = $neighborhood;
        return $this;
    }

    /**
     * @return Neighborhood[]
     */
    public function getNeighborhoods()
    {
        return $this->neighborhoods;
    }

    public function asAssociativeArray(): array
    {
        $return = [];
        foreach ($this->getNeighborhoods() as $neighborhood) {
            $return[$neighborhood->getHandle()] = $neighborhood->getName();
        }
        return $return;
    }

    public function getByHandle(string $neighborhoodHandle): ?Neighborhood
    {
        foreach ($this->neighborhoods as $neighborhood) {
            if ($neighborhood->getHandle() === $neighborhoodHandle) {
                return $neighborhood;
            }
        }
        return null;
    }

}
