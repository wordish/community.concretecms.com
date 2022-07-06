<?php

namespace PortlandLabs\Skyline\Neighborhood\Command\Traits;

trait NeighborhoodTrait
{

    /**
     * @var string
     */
    protected $neighborhood;

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

}
