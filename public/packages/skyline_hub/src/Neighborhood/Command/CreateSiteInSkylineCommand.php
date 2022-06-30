<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

class CreateSiteInSkylineCommand implements NeighborhoodAwareInterface
{

    /**
     * @var string
     */
    protected $neighborhood;

    /**
     * @var string
     */
    protected $siteHandle;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $concreteAdminPassword;

    /**
     * @return string
     */
    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    /**
     * @param string $neighborhood
     */
    public function setNeighborhood(string $neighborhood): void
    {
        $this->neighborhood = $neighborhood;
    }

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

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getConcreteAdminPassword(): string
    {
        return $this->concreteAdminPassword;
    }

    /**
     * @param string $concreteAdminPassword
     */
    public function setConcreteAdminPassword(string $concreteAdminPassword): void
    {
        $this->concreteAdminPassword = $concreteAdminPassword;
    }




}
