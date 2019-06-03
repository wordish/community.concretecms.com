<?php
namespace Application\StartingPointPackage\Concrete5Community;

use Concrete\Core\Application\Application;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Routine\AttachModeInstallRoutine;
use Concrete\Core\Package\StartingPointInstallRoutine;
use Concrete\Core\Package\StartingPointPackage;

class Controller extends StartingPointPackage
{
    protected $pkgHandle = 'concrete5_community';

    public function getPackageName()
    {
        return t('concrete5.org Community');
    }

    public function getPackageDescription()
    {
        return 'Installs the concrete5.org user portal and forums.';
    }
    
}
