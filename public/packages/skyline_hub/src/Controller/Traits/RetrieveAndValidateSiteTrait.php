<?php

namespace PortlandLabs\Skyline\Controller\Traits;

use Concrete\Core\Permission\Checker;
use Concrete\Core\User\User;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;

trait RetrieveAndValidateSiteTrait
{

    protected function retrieveAndValidateSite($id = null, User $u = null): Site
    {
        $entityManager = app(EntityManager::class);
        $entry = $entityManager->find(Site::class, $id);
        if (!$entry) {
            throw new \Exception(t('Invalid site.'));
        }
        if ($u) {
            if ($entry->getAuthor()->getUserID() != $u->getUserID()) {
                throw new \Exception(t('You do not have access to edit this site.'));
            }
        } else {
            $checker = new Checker();
            if (!$checker->canReadSkylineSiteEntries()) {
                throw new \Exception(t('You do not have access to edit this site.'));
            }
        }
        return $entry;
    }

}
