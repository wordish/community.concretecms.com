<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */
/** @noinspection PhpUnused */

namespace PortlandLabs\Skyline\Site;

use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Search\ItemList\Pager\Manager\SiteListPagerManager;
use Concrete\Core\Search\ItemList\Database\ItemList;
use Concrete\Core\Search\ItemList\Pager\PagerProviderInterface;
use Concrete\Core\Search\ItemList\Pager\QueryString\VariableFactory;
use Concrete\Core\Search\Pagination\PaginationProviderInterface;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Permission\Key\Key;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Closure;

class SiteList extends ItemList implements PagerProviderInterface, PaginationProviderInterface
{
    protected $isFulltextSearch = false;
    protected $autoSortColumns = ['t0.name', 't0.handle', 't0.dateCreated', 't0.neighborhood', 't0.status', 't0.suspendedTimestamp'];
    protected $permissionsChecker = -1;

    public function createQuery()
    {
        $this->query->select('t0.*')
            ->from("SkylineSites", "t0");
    }

    public function finalizeQuery(QueryBuilder $query)
    {
        return $query;
    }

    /**
     * @param string $keywords
     */
    public function filterByKeywords($keywords)
    {
        $this->query->andWhere('(t0.`id` LIKE :keywords OR t0.`name` LIKE :keywords OR t0.`handle` LIKE :keywords OR t0.`subscriptionId` LIKE :keywords OR t0.`subscriptionStatus` LIKE :keywords OR t0.`neighborhood` LIKE :keywords OR t0.`adminPassword` LIKE :keywords)');
        $this->query->setParameter('keywords', '%' . $keywords . '%');
    }

    /**
     * @param string $name
     */
    public function filterByName($name)
    {
        $this->query->andWhere('t0.`name` LIKE :name');
        $this->query->setParameter('name', '%' . $name . '%');
    }

    /**
     * @param string $name
     */
    public function filterByAuthorUserID($uID)
    {
        $this->query->andWhere('t0.`uID` = :uID');
        $this->query->setParameter('uID', $uID);
    }

    /**
     * @param string $handle
     */
    public function filterByHandle($handle)
    {
        $this->query->andWhere('t0.`handle` LIKE :handle');
        $this->query->setParameter('handle', '%' . $handle . '%');
    }

    /**
     * @param string $subscriptionId
     */
    public function filterBySubscriptionId($subscriptionId)
    {
        $this->query->andWhere('t0.`subscriptionId` = :subscriptionId');
        $this->query->setParameter('subscriptionId', $subscriptionId);
    }

    /**
     * @param string $subscriptionStatus
     */
    public function filterBySubscriptionStatus($subscriptionStatus)
    {
        $this->query->andWhere('t0.`subscriptionStatus` = :subscriptionStatus');
        $this->query->setParameter('subscriptionStatus', $subscriptionStatus);
    }

    /**
     * @param string $neighborhood
     */
    public function filterByNeighborhood($neighborhood)
    {
        $this->query->andWhere('t0.`neighborhood` = :neighborhood');
        $this->query->setParameter('neighborhood', $neighborhood);
    }

    public function sortByDateAddedDescending()
    {
        $this->query->orderBy('t0.dateCreated', 'desc');
    }

    /**
     * @param string $adminPassword
     */
    public function filterByAdminPassword($adminPassword)
    {
        $this->query->andWhere('t0.`adminPassword` LIKE :adminPassword');
        $this->query->setParameter('adminPassword', '%' . $adminPassword . '%');
    }

    /**
     * @param int $status
     */
    public function filterByStatus($status)
    {
        $this->query->andWhere('t0.`status` = :status');
        $this->query->setParameter('status', $status);
    }

    /**
     * @param int $suspendedTimestamp
     */
    public function filterBySuspendedTimestamp($suspendedTimestamp)
    {
        $this->query->andWhere('t0.`suspendedTimestamp` = :suspendedTimestamp');
        $this->query->setParameter('suspendedTimestamp', $suspendedTimestamp);
    }


    /**
     * @param array $queryRow
     * @return Site
     */
    public function getResult($queryRow)
    {
        $app = Application::getFacadeApplication();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $app->make(EntityManagerInterface::class);
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $entityManager->getRepository(Site::class)->findOneBy(["id" => $queryRow["id"]]);
    }

    public function getTotalResults()
    {
        if ($this->permissionsChecker === -1) {
            return $this->deliverQueryObject()
                ->resetQueryParts(['groupBy', 'orderBy'])
                ->select('count(distinct t0.id)')
                ->setMaxResults(1)
                ->execute()
                ->fetchColumn();
        }

        return -1; // unknown
    }

    public function getPagerManager()
    {
        return new SiteListPagerManager($this);
    }

    public function getPagerVariableFactory()
    {
        return new VariableFactory($this, $this->getSearchRequest());
    }

    public function getPaginationAdapter()
    {
        return new DoctrineDbalAdapter($this->deliverQueryObject(), function ($query) {
            $query->resetQueryParts(['groupBy', 'orderBy'])
                ->select('count(distinct t0.id)')
                ->setMaxResults(1);
        });
    }

    public function checkPermissions($mixed)
    {
        if (isset($this->permissionsChecker)) {
            if ($this->permissionsChecker === -1) {
                return true;
            }

            /** @noinspection PhpParamsInspection */
            return call_user_func_array($this->permissionsChecker, [$mixed]);
        }

        $permissionKey = Key::getByHandle("read_hosting_site_entries");
        return $permissionKey->validate();
    }

    public function setPermissionsChecker(Closure $checker = null)
    {
        $this->permissionsChecker = $checker;
    }

    public function ignorePermissions()
    {
        $this->permissionsChecker = -1;
    }

    public function getPermissionsChecker()
    {
        return $this->permissionsChecker;
    }

    public function enablePermissions()
    {
        unset($this->permissionsChecker);
    }

    public function isFulltextSearch()
    {
        return $this->isFulltextSearch;
    }
}
