<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client\Query;

interface SearchQueryInterface extends QueryInterface
{

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
     * @return mixed
     */
    public function getItemsPerPage(): ?int;

    /**
     * @return array
     */
    public function getQueryParameters(): array;


}
