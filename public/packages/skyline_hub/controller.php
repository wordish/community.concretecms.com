<?php

namespace Concrete\Package\SkylineHub;

use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Database\EntityManager\Provider\ProviderAggregateInterface;
use Concrete\Core\Database\EntityManager\Provider\ProviderInterface;
use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;
use Concrete\Core\Package\Package;
use PortlandLabs\Skyline\ServiceProvider;

class Controller extends Package implements ProviderAggregateInterface
{

    protected $pkgHandle = 'skyline_hub';
    protected $appVersionRequired = '9.0.2';
    protected $pkgVersion = '0.4.0';
    protected $pkgAutoloaderMapCoreExtensions = true;
    protected $pkgAutoloaderRegistries = array(
        'src' => '\PortlandLabs\Skyline',

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
        $this->on_start();
        parent::install();
        $this->installContentFile('data.xml');
    }

    public function uninstall()
    {
        parent::uninstall();
        $db = $this->app->make(Connection::class);
        $db->executeUpdate('drop table if exists SkylineSites');
        $db->executeUpdate('drop table if exists SavedSkylineSiteSearchQueries');
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
        $al->register("css", "skyline/frontend", "css/skyline/frontend.css", [], "skyline_hub");
        $al->register("javascript", "skyline/frontend", "js/skyline/frontend.js", ["position" => Asset::ASSET_POSITION_FOOTER], "skyline_hub");
        $al->registerGroup('skyline/frontend', [
            ['javascript', 'vue'],
            ['css', 'skyline/frontend'],
            ['javascript', 'skyline/frontend'],
        ]);
    }

    /**
     * @return ProviderInterface
     */
    public function getEntityManagerProvider()
    {
        return new StandardPackageProvider($this->app, $this, [
            'src/Entity' => "\PortlandLabs\Skyline\Entity"
        ]);
    }


}
