<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use PortlandLabs\Skyline\Neighborhood\Command\Traits\NeighborhoodAccountTrait;

class CreateSiteInNeighborhoodCommand implements NeighborhoodAwareInterface
{

    use NeighborhoodAccountTrait;

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
