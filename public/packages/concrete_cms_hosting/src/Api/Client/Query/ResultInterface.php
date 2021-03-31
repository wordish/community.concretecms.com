<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query;

interface ResultInterface
{

    /**
     * @return int
     */
    public function getTotalItems();

    /**
     * @return array[]
     */
    public function getMembers();
}
