<?php

namespace PortlandLabs\Skyline\Neighborhood\Command\Traits;

use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;

trait UpdateAccountTrait
{

    public function getEntityManager()
    {
        return app(EntityManager::class);
    }

    public function clearEntityManager()
    {
        return $this->getEntityManager()->clear();
    }

    /**
     * @param string $neighborhood
     * @param string $siteHandle
     * @return Site
     */
    public function getSite(string $neighborhood, string $siteHandle): ?Site
    {
        $site = $this->getEntityManager()->getRepository(Site::class)
            ->findOneBy(['neighborhood' => $neighborhood, 'handle' => $siteHandle]);
        return $site;
    }

    public function setStatus(string $neighborhood, string $siteHandle, \Closure $callback)
    {
        $site = $this->getSite($neighborhood, $siteHandle);
        if ($site) {
            $site = $callback($site);
            $entityManager = $this->getEntityManager();
            $entityManager->persist($site);
            $entityManager->flush();
        }
    }


}
