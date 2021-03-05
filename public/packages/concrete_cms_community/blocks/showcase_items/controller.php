<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\ConcreteCmsCommunity\Block\ShowcaseItems;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Express\EntryList;
use Concrete\Core\Express\ObjectManager;
use Concrete\Core\Page\Page;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;

class Controller extends BlockController
{
    protected $btTable = "btShowcaseItems";

    public function getBlockTypeDescription()
    {
        return t('Integrate showcase items into users profile page.');
    }

    public function getBlockTypeName()
    {
        return t('Showcase items');
    }

    public function view()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = $this->app->make(ObjectManager::class);

        $currentPage = Page::getCurrentPage();
        $profile = $currentPage->getPageController()->get("profile");
        $currentUser = new User();
        $isOwnProfile = false;
        $showcaseItems = [];

        if ($profile instanceof UserInfo) {
            if ($currentUser->isRegistered() && $profile->getUserID() == $currentUser->getUserID()) {
                $isOwnProfile = true;
            }

            $showcaseEntity = $objectManager->getObjectByHandle("showcase_item");
            $showcaseItemsEntryList = new EntryList($showcaseEntity);
            $showcaseItemsEntryList->filterByAttribute("author", $profile->getEntityObject());
            /** @var Entry[] $showcaseItems */
            $showcaseItems = $showcaseItemsEntryList->getResults();
        }

        $this->set('showcaseItems', $showcaseItems);
        $this->set('profile', $profile);
        $this->set('isOwnProfile', $isOwnProfile);
    }
}
