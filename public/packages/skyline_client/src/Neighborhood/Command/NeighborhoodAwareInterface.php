<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Concrete\Core\Foundation\Command\Command;

interface NeighborhoodAwareInterface
{

    public function getNeighborhood(): string;


}
