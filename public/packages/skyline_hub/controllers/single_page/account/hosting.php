<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Account;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Express\ObjectManager;
use Concrete\Core\Page\Controller\AccountPageController;
use Concrete\Core\Page\Controller\PageController;
use PortlandLabs\Skyline\Site\Site;
use PortlandLabs\Skyline\Site\SiteFactory;

class Hosting extends AccountPageController
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

    public function install($uuid  = null)
    {
        $hostingSite = $this->protect($uuid);
        if ($hostingSite->getStatus() != Site::STATUS_INSTALLING) {
            return $this->buildRedirect(['/account/hosting/', 'view_details', $uuid]);
        }

        $this->render('/account/hosting/install');
    }

    public function view_details($uuid = null)
    {
        $hostingSite = $this->protect($uuid);
        $subscription = $hostingSite->getSubscription();
        $this->set('subscription', $subscription);
        $this->render('/account/hosting/details');
    }
}