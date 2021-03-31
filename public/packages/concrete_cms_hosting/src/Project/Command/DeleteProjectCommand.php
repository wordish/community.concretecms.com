<?php

namespace PortlandLabs\Hosting\Project\Command;

class DeleteProjectCommand
{

    /**
     * @var int
     */
    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }


}
