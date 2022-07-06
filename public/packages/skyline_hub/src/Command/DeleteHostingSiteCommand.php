<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Foundation\Command\Command;

class DeleteHostingSiteCommand extends Command
{

    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }



}
