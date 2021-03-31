<?php

namespace PortlandLabs\Hosting\Project\Command;

class CreateLagoonProjectCommand extends CreateProjectCommand
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
