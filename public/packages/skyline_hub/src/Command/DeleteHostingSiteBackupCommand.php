<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Foundation\Command\Command;

class DeleteHostingSiteBackupCommand extends Command
{

    protected $backupId;

    public function __construct($backupId)
    {
        $this->backupId = $backupId;
    }

    /**
     * @return mixed
     */
    public function getBackupId()
    {
        return $this->backupId;
    }



}
