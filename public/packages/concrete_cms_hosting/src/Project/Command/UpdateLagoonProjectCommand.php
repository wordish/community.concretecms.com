<?php

namespace PortlandLabs\Hosting\Project\Command;

class UpdateLagoonProjectCommand extends UpdateProjectCommand
{

    /**
     * @var string
     */
    protected $lagoonId;

    /**
     * @return string
     */
    public function getLagoonId(): string
    {
        return $this->lagoonId;
    }

    /**
     * @param string $lagoonId
     */
    public function setLagoonId(string $lagoonId): void
    {
        $this->lagoonId = $lagoonId;
    }




}
