<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query\Resource;

use PortlandLabs\Hosting\Api\Client\Query\QueryInterface;

class GetConcreteReleasesQuery implements QueryInterface
{

    public function getEndpoint(): string
    {
        return '/concrete_releases?order[dateReleased]=desc';
    }




}
