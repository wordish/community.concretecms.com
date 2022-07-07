<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Concrete\Core\Foundation\Command\Command;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\NeighborhoodAccountTrait;

class CompleteAccountDeletionInHubCommand extends Command
{

    use NeighborhoodAccountTrait;

}
