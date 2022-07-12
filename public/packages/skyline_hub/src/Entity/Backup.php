<?php

namespace PortlandLabs\Skyline\Entity;

use Concrete\Core\Utility\Service\Number;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="SkylineSiteBackups")
 */
class Backup implements \JsonSerializable
{

    /**
     * @ORM\Id @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="backups")
     */
    protected $site;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $filename;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true}, nullable=true)
     */
    protected $size;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $backupFileID;

    /**
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    protected $dateCreated;

    public function __construct()
    {
        $this->dateCreated = time();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site): void
    {
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return int
     */
    public function getDateCreated(string $format = null)
    {
        if ($format) {
            return (new \DateTime())->setTimestamp($this->getDateCreated())->format($format);
        } else {
            return $this->dateCreated;
        }
    }

    /**
     * @param int $dateCreated
     */
    public function setDateCreated(int $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getBackupFileID()
    {
        return $this->backupFileID;
    }

    /**
     * @param mixed $backupFileID
     */
    public function setBackupFileID($backupFileID): void
    {
        $this->backupFileID = $backupFileID;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size): void
    {
        $this->size = $size;
    }

    public function isLoading()
    {
        return $this->size === null && $this->backupFileID === null && $this->filename === null;
    }

    public function jsonSerialize()
    {
        $numberHelper = new Number();
        $dashboardDownloadUrl = (string) \URL::to('/dashboard/skyline/sites/details', 'download_backup', $this->getId());
        return [
            'id' => $this->getId(),
            'size' => $this->getSize(),
            'isLoading' => $this->isLoading(),
            'dateCreated' => $this->getDateCreated(),
            'dateCreatedString' => $this->getDateCreated('F d Y \a\t g:i a'),
            'sizeString' => $numberHelper->formatSize($this->getSize()),
            'filename' => $this->getFilename(),
            'backupFileId' => $this->getBackupFileID(),
            'site' => $this->getSite(),
            'dashboardDownloadUrl' => $dashboardDownloadUrl,
        ];
    }


}
