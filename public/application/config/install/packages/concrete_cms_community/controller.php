<?php
namespace Application\StartingPointPackage\ConcreteCmsCommunity;

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
        $site = $this->app->make('site')->getActiveSiteForEditing();
        $siteConfig = $site->getConfigRepository();
        $siteConfig->save("concrete_cms_theme.enable_dark_mode", true);
    }
}
