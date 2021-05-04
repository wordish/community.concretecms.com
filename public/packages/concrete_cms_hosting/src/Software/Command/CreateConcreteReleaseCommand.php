<?php

namespace PortlandLabs\Hosting\Software\Command;

class CreateConcreteReleaseCommand
{

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $dateReleased;

    /**
     * @var string
     */
    protected $downloadUrl;

    /**
     * @var string
     */
    protected $releaseNotes;

    /**
     * @var string
     */
    protected $releaseNotesUrl;

    /**
     * @var string
     */
    protected $upgradeNotes;

    /**
     * @var bool
     */
    protected $isPublished = true;

    /**
     * @var string
     */
    protected $md5sum;

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getDateReleased(): string
    {
        return $this->dateReleased;
    }

    /**
     * @param string $dateReleased
     */
    public function setDateReleased(string $dateReleased): void
    {
        $this->dateReleased = $dateReleased;
    }

    /**
     * @return string
     */
    public function getDownloadUrl(): string
    {
        return $this->downloadUrl;
    }

    /**
     * @param string $downloadUrl
     */
    public function setDownloadUrl(string $downloadUrl): void
    {
        $this->downloadUrl = $downloadUrl;
    }

    /**
     * @return string
     */
    public function getReleaseNotes(): string
    {
        return $this->releaseNotes;
    }

    /**
     * @param string $releaseNotes
     */
    public function setReleaseNotes(string $releaseNotes): void
    {
        $this->releaseNotes = $releaseNotes;
    }

    /**
     * @return string
     */
    public function getReleaseNotesUrl(): string
    {
        return $this->releaseNotesUrl;
    }

    /**
     * @param string $releaseNotesUrl
     */
    public function setReleaseNotesUrl(string $releaseNotesUrl): void
    {
        $this->releaseNotesUrl = $releaseNotesUrl;
    }

    /**
     * @return string
     */
    public function getUpgradeNotes(): string
    {
        return $this->upgradeNotes;
    }

    /**
     * @param string $upgradeNotes
     */
    public function setUpgradeNotes(string $upgradeNotes): void
    {
        $this->upgradeNotes = $upgradeNotes;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    /**
     * @param bool $isPublished
     */
    public function setIsPublished(bool $isPublished): void
    {
        $this->isPublished = $isPublished;
    }

    /**
     * @return string
     */
    public function getMd5sum(): string
    {
        return $this->md5sum;
    }

    /**
     * @param string $md5sum
     */
    public function setMd5sum(string $md5sum): void
    {
        $this->md5sum = $md5sum;
    }





}
