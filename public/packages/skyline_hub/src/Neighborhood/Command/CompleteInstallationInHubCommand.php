<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Concrete\Core\Foundation\Command\Command;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\NeighborhoodAccountTrait;

class CompleteInstallationInHubCommand extends Command
{

    use NeighborhoodAccountTrait;

    /**
     * @var int
     */
    protected $bytesUsed;

    /**
     * @return int
     */
    public function getBytesUsed(): int
    {
        return $this->bytesUsed;
    }

    /**
     * @param int $bytesUsed
     */
    public function setBytesUsed(int $bytesUsed): void
    {
        $this->bytesUsed = $bytesUsed;
    }



}
