<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Concrete\Core\Foundation\Command\Command;
use PortlandLabs\Skyline\Neighborhood\Command\Traits\NeighborhoodAccountTrait;

class CreateAccountBackupRecordInHubCommand extends Command
{

    use NeighborhoodAccountTrait;

    /**
     * @var string
     */
    protected $file;

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile(string $file): void
    {
        $this->file = $file;
    }


}
