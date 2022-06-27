<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Foundation\Command\Command;

interface NeighborhoodAwareInterface
{

    public function getNeighborhood(): string;


}
