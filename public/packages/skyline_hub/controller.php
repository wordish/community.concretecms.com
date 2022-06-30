<?php

namespace Concrete\Package\SkylineHub;

use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Package\Package;
use PortlandLabs\Skyline\ServiceProvider;

class Controller extends Package
{

    protected $pkgHandle = 'skyline_hub';
    protected $appVersionRequired = '9.0.2';
    protected $pkgVersion = '0.2.4';
    protected $pkgAutoloaderMapCoreExtensions = true;
    protected $pkgAutoloaderRegistries = array(
        'src' => '\PortlandLabs\Skyline'
    );

    public function getPackageDescription()
    {
        return t("Skyline Hosting Platform Hub.");
    }

    public function getPackageName()
    {
        return t("Skyline Hub");
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

        $al = AssetList::getInstance();
        $al->register("javascript", "skyline/frontend", "js/frontend/skyline.js", ["position" => Asset::ASSET_POSITION_FOOTER], "skyline_hub");
        $al->registerGroup('skyline/frontend', [
            ['javascript', 'vue'],
            ['javascript', 'skyline/frontend'],
        ]);

    }

}
