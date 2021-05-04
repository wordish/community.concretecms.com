<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Software;

use Michelf\Markdown;

class ConcreteRelease
{

    /**
     * @var string
     */
    protected $id;

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
     * @var boolean
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
     * @return \DateTime
     */
    public function getDateReleased(): \DateTime
    {
        return new \DateTime($this->dateReleased);
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

    public function getDateReleasedString(): string
    {
        if ($this->getDateReleased()) {
            return $this->getDateReleased()->format('F d, Y');
        }
        return '';
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
    public function getUpgradeNotes(): ?string
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

    public function getUpgradeNotesFormatted()
    {
        return Markdown::defaultTransform($this->getUpgradeNotes());
    }

    public function getReleaseNotesFormatted()
    {
        return Markdown::defaultTransform($this->getReleaseNotes());
    }


}
