<?php

namespace PortlandLabs\Skyline\Events\ServerEvent;

class BackupUpdated extends AbstractBackupEvent
{

    public function getEvent(): string
    {
        return 'SkylineBackupUpdated';
    }


}

