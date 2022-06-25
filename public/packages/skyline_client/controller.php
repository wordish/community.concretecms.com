<?php

namespace Concrete\Package\SkylineClient;

use Concrete\Core\Package\Package;
use PortlandLabs\Skyline\ServiceProvider;

class Controller extends Package
{

    protected $pkgHandle = 'skyline_client';
    protected $appVersionRequired = '9.0.2';
    protected $pkgVersion = '0.2.3';
    protected $pkgAutoloaderMapCoreExtensions = true;
    protected $pkgAutoloaderRegistries = array(
        'src' => '\PortlandLabs\Skyline'
    );

    public function getPackageDescription()
    {
        return t("Skyline Hosting Platform Client Extensions.");
    }

    public function getPackageName()
    {
        return t("Skyline Client");
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
        if (file_exists($this->getPackagePath() . "/vendor")) {
            require_once $this->getPackagePath() . "/vendor/autoload.php";
        }

        $provider = $this->app->make(ServiceProvider::class);
        $provider->register();
    }

}
