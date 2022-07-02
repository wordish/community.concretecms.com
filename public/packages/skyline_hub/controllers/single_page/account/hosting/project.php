<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Account\Hosting;

use Concrete\Core\Express\ObjectManager;
use Concrete\Core\Page\Controller\AccountPageController;
use PortlandLabs\Skyline\Site\Site;
use PortlandLabs\Skyline\Site\SiteFactory;

class Project extends AccountPageController
{

    public function on_start()
    {
        parent::on_start();
        $this->requireAsset('skyline/frontend');
    }

    protected function protect($uuid = null): Site
    {
        /**
         * @var $objectManager ObjectManager
         */
        $objectManager = $this->app->make(ObjectManager::class);
        $entry = $objectManager->getEntryByPublicIdentifier($uuid);
        if (!$entry) {
            throw new \Exception(t('Invalid site.'));
        }
        $profile = $this->get('profile');
        if ($entry->getAuthor()->getUserID() !== $profile->getUserID()) {
            throw new \Exception(t('You do not have access to edit this site.'));
        }
        $siteFactory = $this->app->make(SiteFactory::class);
        $hostingSite = $siteFactory->createFromEntry($entry);
        $this->set('hostingSite', $hostingSite);
        return $hostingSite;
    }

    public function view($uuid = null)
    {
        $hostingSite = $this->protect($uuid);
        if ($hostingSite->getStatus() == Site::STATUS_INSTALLING) {
            $this->render('/account/hosting/project/install');
        } else {
            $this->render('/account/hosting/project/details');
        }
    }
}