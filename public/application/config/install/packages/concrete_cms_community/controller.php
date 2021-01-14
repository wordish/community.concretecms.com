<?php
namespace Application\StartingPointPackage\ConcreteCmsCommunity;

use Concrete\Core\Application\Application;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Routine\AttachModeInstallRoutine;
use Concrete\Core\Package\StartingPointInstallRoutine;
use Concrete\Core\Package\StartingPointPackage;

class Controller extends StartingPointPackage
{
    protected $pkgHandle = 'concrete_cms_community';

    public function getPackageName()
    {
        return t('community.concretecms.com');
    }

    public function getPackageDescription()
    {
        return 'Installs the community.concretecms.com starting point.';
    }
    
}
