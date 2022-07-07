<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Concrete\Core\Foundation\Command\Command;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\NeighborhoodTrait;

class UpdateDiskUsageInHubCommand extends Command
{

    use NeighborhoodTrait;

    /**
     * An associative array of ['accountHandle' => 'sizeInBytes']
     * @var array
     */
    protected $sizes;

    /**
     * @return array
     */
    public function getSizes(): array
    {
        return $this->sizes;
    }

    /**
     * @param array $sizes
     */
    public function setSizes(array $sizes): void
    {
        $this->sizes = $sizes;
    }


}
