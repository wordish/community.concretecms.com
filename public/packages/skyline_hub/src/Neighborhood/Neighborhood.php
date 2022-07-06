<?php

namespace PortlandLabs\Skyline\Neighborhood;

use PortlandLabs\Skyline\Entity\Site;

class Neighborhood
{

    /**
     * @var string
     */
    protected $handle;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var bool
     */
    protected $useSsl;

    public function __construct(string $handle, string $name, $domain, $useSsl = true)
    {
        $this->handle = $handle;
        $this->name = $name;
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
