<?php

namespace PortlandLabs\Hosting\Software\Command;

class DeleteConcreteReleaseCommand
{

    /**
     * @var int
     */
    protected $releaseId;

    public function __construct($releaseId)
    {
        $this->releaseId = $releaseId;
    }

    /**
     * @return int
     */
    public function getReleaseId(): int
    {
        return $this->releaseId;
    }


}
