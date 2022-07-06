<?php

namespace PortlandLabs\Skyline\Controller\Traits;

use Concrete\Core\Permission\Checker;
use Concrete\Core\User\User;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;

trait RetrieveAndValidateSiteTrait
{

    private function getHostingSite($id): Site
    {
        $entityManager = app(EntityManager::class);
        $entry = $entityManager->find(Site::class, $id);
        if (!$entry) {
            throw new \Exception(t('Invalid site.'));
        }
        return $entry;
    }

    protected function retrieveAndValidateSiteForViewing($id = null): Site
    {
        $entry = $this->getHostingSite($id);
        $u = new User();
        if ($entry->getAuthor()->getUserID() != $u->getUserID()) {
            $checker = new Checker();
            if (!$checker->canReadSkylineSiteEntries()) {
                throw new \Exception(t('You do not have access to edit this site.'));
            }
        }
        return $entry;
    }

    protected function retrieveAndValidateSiteForEditing($id = null): Site
    {
        $entry = $this->getHostingSite($id);
        $checker = new Checker();
        if (!$checker->canUpdateSkylineSiteEntries()) {
            throw new \Exception(t('You do not have access to edit this site.'));
        }
        return $entry;
    }

    protected function retrieveAndValidateSiteForDeleting($id = null): Site
    {
        $entry = $this->getHostingSite($id);
        $checker = new Checker();
        if (!$checker->canDeleteSkylineSiteEntries()) {
            throw new \Exception(t('You do not have access to edit this site.'));
        }
        return $entry;
    }



}
