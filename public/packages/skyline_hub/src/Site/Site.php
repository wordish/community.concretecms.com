<?php

namespace PortlandLabs\Skyline\Site;

use Concrete\Core\Entity\Express\Entry;

class Site implements \JsonSerializable
{

    const STATUS_INSTALLING = 10;
    const STATUS_INSTALLED = 50;
    const STATUS_TERMINATED = 999;

    /**
     * Site UUID; derived from the Express public identifier.
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $status;

    /**
     * Semi-autogenerated handle of the site. Matches the default domain for viewing/editing.
     * e.g. kettlfo-jcid7-iejfl
     *
     * @var string
     */
    protected $handle;

    /**
     * Public URL at which the site can be accessed.
     * @var string
     */
    protected $publicUrl;

    /**
     * Domain to display on the site details page.
     * @var string
     */
    protected $publicDomain;

    /**
     * Auto-generated password, temporarily available on the Site object during installation. Is cleared when
     * installation is complete.
     *
     * @var string
     */
    protected $concreteAdminPassword;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPublicUrl(): string
    {
        return $this->publicUrl;
    }

    /**
     * @param string $publicUrl
     */
    public function setPublicUrl(string $publicUrl): void
    {
        $this->publicUrl = $publicUrl;
    }

    /**
     * @return string
     */
    public function getPublicDomain(): string
    {
        return $this->publicDomain;
    }

    /**
     * @param string $publicDomain
     */
    public function setPublicDomain(string $publicDomain): void
    {
        $this->publicDomain = $publicDomain;
    }

    /**
     * @param string $handle
     */
    public function setHandle(string $handle): void
    {
        $this->handle = $handle;
    }

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
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

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'handle' => $this->handle,
            'status' => $this->status,
            'publicDomain' => $this->publicDomain,
            'publicUrl' => $this->publicUrl,
            'password' => $this->concreteAdminPassword,
        ];
    }
}
