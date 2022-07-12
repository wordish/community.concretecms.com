<?php

namespace PortlandLabs\Skyline\Events\ServerEvent;

use Concrete\Core\Notification\Events\ServerEvent\EventInterface;
use PortlandLabs\Skyline\Entity\Backup;

abstract class AbstractBackupEvent implements EventInterface
{

    /**
     * @var Backup
     */
    protected $backup;

    /**
     * @param Backup $backup
     */
    public function __construct(Backup $backup)
    {
        $this->backup = $backup;
    }

    public function getData(): array
    {
        return ['backup' => $this->backup];
    }

}

