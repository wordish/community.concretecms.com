<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query;

interface QueryInterface
{

    /**
     * @return string
     */
    public function getEndpoint() : string;


}
