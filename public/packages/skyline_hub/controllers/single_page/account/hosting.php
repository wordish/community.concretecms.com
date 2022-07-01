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

    public function install($uuid  = null)
    {
        /**
         * @var $objectManager ObjectManager
         */
        $objectManager = $this->app->make(ObjectManager::class);
        $hostingSite = $objectManager->getEntryByPublicIdentifier($uuid);
        if (!$hostingSite) {
            throw new \Exception(t('Invalid site.'));
        }
        $profile = $this->get('profile');
        if ($hostingSite->getAuthor()->getUserID() !== $profile->getUserID()) {
            throw new \Exception(t('You do not have access to edit this site.'));
        }
        $siteFactory = $this->app->make(SiteFactory::class);
        $this->set('hostingSite', $siteFactory->createFromEntry($hostingSite));
        $this->render('/account/hosting/install');
    }

}