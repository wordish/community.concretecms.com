<?php

namespace Concrete\Package\ConcreteCmsHosting\Block\NewHostingProjectWizard;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Express\EntryList;
use Concrete\Core\Express\ObjectManager;
use Concrete\Core\Page\Page;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;

class Controller extends BlockController
{
    public function getBlockTypeDescription()
    {
        return t('Creates a wizard interface to select a new hosting project.');
    }

    public function getBlockTypeName()
    {
        return t('Hosting Wizard');
    }

    public function view()
    {

    }
}
