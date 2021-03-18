<?php

namespace Concrete\Package\ConcreteCmsHosting\Block\NewHostingProjectWizard;

use Concrete\Core\Block\BlockController;

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
