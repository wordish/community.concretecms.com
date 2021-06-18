<?php

namespace Concrete\Package\ConcreteCmsHosting;

use Concrete\Core\Package\Package;
use PortlandLabs\Hosting\ServiceProvider;

class Controller extends Package
{

    protected $pkgHandle = 'concrete_cms_hosting';
    protected $appVersionRequired = '9.0.0a1';
    protected $pkgVersion = '0.1';
    protected $pkgAutoloaderMapCoreExtensions = true;
    protected $pkgAutoloaderRegistries = array(
        'src' => '\PortlandLabs\Hosting'
    );

    public function getPackageDescription()
    {
        return t("The hosting.concretecms.com extensions.");
    }

    public function getPackageName()
    {
        return t("hosting.concretecms.com");
    }

    public function install()
    {
        parent::install();
        $this->installContentFile('data.xml');
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile('data.xml');
    }
    
    public function on_start()
    {
        // Register our service providers
        /*
         * Currently broken; something about enabling the ApiServiceProvider breaks the user avatar upload process
         * :shrug: new-484. Re-enable this when it's time to fix this and enable the hosting.
        $this->app->make(ServiceProvider::class)->register();
        */
    }
}
