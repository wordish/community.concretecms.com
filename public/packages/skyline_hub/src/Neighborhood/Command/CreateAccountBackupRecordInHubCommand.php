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
     * @var string
     */
    protected $backupFileID;

    /**
     * @var integer
     */
    protected $size;

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

    /**
     * @return string
     */
    public function getBackupFileID(): string
    {
        return $this->backupFileID;
    }

    /**
     * @param string $backupFileID
     */
    public function setBackupFileID(string $backupFileID): void
    {
        $this->backupFileID = $backupFileID;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }




}
