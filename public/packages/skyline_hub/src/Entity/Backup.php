<?php

namespace PortlandLabs\Skyline\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="SkylineSiteBackups")
 */
class Backup
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
     * @return mixed
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
    public function getDateCreated(): int
    {
        return $this->dateCreated;
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






}
