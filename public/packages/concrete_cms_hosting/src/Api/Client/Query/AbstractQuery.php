<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query;

use GuzzleHttp\Client;

abstract class AbstractQuery implements QueryInterface
{

    /**
     * @var int|null
     */
    protected $itemsPerPage;

    /**
     * @var int|null
     */
    protected $currentPage;

    /**
     * @var array
     */
    protected $queryParameters = [];


    public function addOrderBy($orderBy, $direction)
    {
        $this->queryParameters['order'][$orderBy] = $direction;
    }

    /**
     * @param int|null $itemsPerPage
     */
    public function setItemsPerPage(?int $itemsPerPage): void
    {
        $this->queryParameters['itemsPerPage'] = $itemsPerPage;
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @param int|null $currentPage
     */
    public function setCurrentPage(?int $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return array
     */
    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }

    /**
     * @return int|null
     */
    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }




}
