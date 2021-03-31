<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Project;

use Concrete\Core\Http\Request;
use Concrete\Core\Search\ItemList\ItemList;
use Concrete\Core\Search\Pagination\Pagination;
use Concrete\Core\Search\Pagination\PaginationFactory;
use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Pagination\Adapter\QueryAdapter;
use PortlandLabs\Hosting\Api\Client\Query\Resource\SearchProjectsQuery;

class ProjectList extends ItemList
{

    protected $autoSortColumns = [
        'id',
        'name',
        'dateCreated',
    ];

    /**
     * @var SearchProjectsQuery
     */
    protected $projectQuery;

    public function __construct(SearchProjectsQuery $projectQuery)
    {
        $this->projectQuery = $projectQuery;
    }

    public function executeGetResults()
    {
        throw new \exception('executeGetResults');
    }

    public function debugStart()
    {
        return false;
    }

    public function debugStop()
    {
        return false;
    }

    public function getResult($mixed)
    {
        return $mixed;
    }

    public function executeSortBy($field, $direction = 'asc')
    {
        $this->projectQuery->addOrderBy($field, $direction);
    }

    public function getTotalResults()
    {
        throw new \exception('getTotalResults');
    }

    public function filterByKeywords($keywords)
    {
        $this->projectQuery->setSearchKeywords($keywords);
    }

    public function getPagination()
    {
        $client = app(Client::class);
        $request = Request::createFromGlobals();

        if ($this->itemsPerPage > -1) {
            $this->projectQuery->setItemsPerPage($this->itemsPerPage);
        }

        $adapter = new QueryAdapter($client, $this->projectQuery);
        $pagination = new Pagination($this, $adapter);

        $paginationFactory = new PaginationFactory($request);
        $pagination = $paginationFactory->deliverPaginationObject($this, $pagination);

        return $pagination;
    }

}
