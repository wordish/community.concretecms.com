<?php

namespace PortlandLabs\Skyline\Events\ServerEvent;

class BackupCreated extends AbstractBackupEvent
{

    public function getEvent(): string
    {
        return 'SkylineBackupCreated';
    }

}

