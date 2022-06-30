<?php

namespace PortlandLabs\Skyline\Neighborhood;

use PortlandLabs\Skyline\Site\Site;

class Neighborhood
{

    /**
     * @var string
     */
    protected $handle;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var bool
     */
    protected $useSsl;

    public function __construct(string $handle, $domain, $useSsl = true)
    {
        $this->handle = $handle;
        $this->domain = $domain;
        $this->useSsl = $useSsl;
    }

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    public function getSitePublicUrl(Site $site)
    {
        $protocol = $this->useSsl ? 'https://' : 'http://';
        return $protocol . $this->getSitePublicDomain($site);
    }

    public function getSitePublicDomain(Site $site)
    {
        return $site->getHandle() . '.' . $this->getDomain();
    }




}
