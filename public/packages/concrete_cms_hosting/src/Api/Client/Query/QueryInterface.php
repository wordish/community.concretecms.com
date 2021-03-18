<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query;

interface QueryInterface
{

    /**
     * @return string
     */
    public function getEndpoint() : string;

    /**
     * @param $orderBy
     * @param $direction
     * @return mixed
     */
    public function addOrderBy($orderBy, $direction);

    /**
     * @param int|null $currentPage
     * @return mixed
     */
    public function setCurrentPage(?int $currentPage);

    /**
     * @param $itemsPerPage
     * @return mixed
     */
    public function setItemsPerPage(?int $itemsPerPage);

    /**
     * @return array
     */
    public function getQueryParameters(): array;

    /**
     * @return mixed
     */
    public function getItemsPerPage(): ?int;

}
