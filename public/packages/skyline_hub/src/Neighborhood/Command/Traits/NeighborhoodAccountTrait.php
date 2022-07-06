<?php

namespace PortlandLabs\Skyline\Neighborhood\Command\Traits;

trait NeighborhoodAccountTrait
{

    use NeighborhoodTrait;

    /**
     * @var string
     */
    protected $siteHandle;

    /**
     * @return string
     */
    public function getSiteHandle(): string
    {
        return $this->siteHandle;
    }

    /**
     * @param string $siteHandle
     */
    public function setSiteHandle(string $siteHandle): void
    {
        $this->siteHandle = $siteHandle;
    }


}
