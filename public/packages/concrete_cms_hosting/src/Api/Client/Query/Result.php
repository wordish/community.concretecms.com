<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query;

use GuzzleHttp\Client;

class Result implements ResultInterface
{

    /**
     * @var int
     */
    protected $totalItems = 0;

    /**
     * @var array
     */
    protected $members = [];

    /**
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * @param int $totalItems
     */
    public function setTotalItems(int $totalItems): void
    {
        $this->totalItems = $totalItems;
    }

    public function add($object)
    {
        $this->members[] = $object;
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param array $members
     */
    public function setMembers(array $members): void
    {
        $this->members = $members;
    }




}
