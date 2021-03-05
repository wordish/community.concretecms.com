<?php
namespace Application\StartingPointPackage\ConcreteCmsCommunity;

use Concrete\Core\Application\Application;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Package\Routine\AttachModeInstallRoutine;
use Concrete\Core\Package\StartingPointInstallRoutine;
use Concrete\Core\Package\StartingPointPackage;

class Controller extends StartingPointPackage
{
    protected $pkgHandle = 'concrete_cms_community';

    public function getPackageName()
    {
        return t('concretecms.org');
    }

    public function getPackageDescription()
    {
        return 'Installs the concretecms.org starting point.';
    }

    protected function finish()
    {
        parent::finish();
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);
        $config->save("concrete_cms_theme.enable_dark_mode", true);
    }
}
