<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Project;

class LagoonProject extends Project
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

    public function getSiteTypeString()
    {
        return t('Concrete Hosting');
    }

    public function getSiteType()
    {
        return 'lagoon';
    }



}
