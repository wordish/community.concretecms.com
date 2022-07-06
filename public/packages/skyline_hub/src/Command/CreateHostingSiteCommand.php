<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Foundation\Command\Command;

class CreateHostingSiteCommand extends Command
{

    /**
     * @var string
     */
    protected $siteName;

    /**
     * @var bool
     */
    protected $provisionAccount = true;

    /**
     * @var int
     */
    protected $author;

    /**
     * @var string
     */
    protected $neighborhood;

    /**
     * @var bool
     */
    protected $attachToTestClock = false;

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * @param string $siteName
     */
    public function setSiteName(string $siteName): void
    {
        $this->siteName = $siteName;
    }

    /**
     * @return int
     */
    public function getAuthor(): int
    {
        return $this->author;
    }

    /**
     * @param int $author
     */
    public function setAuthor(int $author): void
    {
        $this->author = $author;
    }



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
     * @return bool
     */
    public function useTestClock(): bool
    {
        return $this->attachToTestClock;
    }

    /**
     * @param bool $attachToTestClock
     */
    public function setAttachToTestClock(bool $attachToTestClock): void
    {
        $this->attachToTestClock = $attachToTestClock;
    }

    /**
     * @return bool
     */
    public function provisionAccount(): bool
    {
        return $this->provisionAccount;
    }

    /**
     * @param bool $provisionAccount
     */
    public function setProvisionAccount(bool $provisionAccount): void
    {
        $this->provisionAccount = $provisionAccount;
    }






}
