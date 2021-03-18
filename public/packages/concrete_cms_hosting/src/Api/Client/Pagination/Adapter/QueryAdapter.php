<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Pagination\Adapter;

use Pagerfanta\Adapter\AdapterInterface;
use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Query\QueryInterface;

class QueryAdapter implements AdapterInterface
{

    /**
     * @var QueryInterface
     */
    protected $query;

    /**
     * @var
     */
    protected $client;

    public function __construct(Client $client, QueryInterface $query)
    {
        $this->client = $client;
        $this->query = $query;
    }

    public function getNbResults()
    {
        $result = $this->client->executeQuery($this->query);
        return $result->getTotalItems();
    }

    public function getSlice($offset, $length)
    {
        // Since the API works by pages and not by offset, we have to calculate our current page based on offset
        // which is backwards because pagerfanta just did this same calculation in reverse :shrug:
        $query = $this->query;
        $currentPage = $offset / $query->getItemsPerPage();
        $currentPage = $currentPage + 1; // Since the API is 1-based not 0-based our first page begins at 1 not 0
        $query->setCurrentPage($currentPage);
        $result = $this->client->executeQuery($query);
        return $result->getMembers();
    }


}
