<?php

declare(strict_types=1);

namespace PortlandLabs\Community;

use Concrete\Core\Application\Application;
use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Page\Theme\ThemeRouteCollection;
use PortlandLabs\Community\API\ServiceProvider as ApiServiceProvider;
use PortlandLabs\Community\Discourse\ServiceProvider as DiscourseProvider;
use PortlandLabs\ConcreteCmsTheme\Navigation\HeaderNavigationFactory;

class ServiceProvider extends Provider
{
    protected $providers = [
        DiscourseProvider::class, // Discourse connect SSO
        ApiServiceProvider::class
    ];

    /**
     * @var ThemeRouteCollection
     */
    protected $themeRouteCollection;

    public function __construct(
        Application $app,
        ThemeRouteCollection $themeRouteCollection)
    {
        parent::__construct($app);
        $this->themeRouteCollection = $themeRouteCollection;
    }

    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->make($provider)->register();
        }

        $al = AssetList::getInstance();
        $al->register("javascript", "community/teams", "js/teams.js", ["position" => Asset::ASSET_POSITION_FOOTER], "concrete_cms_community");
        $al->register("javascript", "community/karma", "js/karma.js", ["position" => Asset::ASSET_POSITION_FOOTER], "concrete_cms_community");

        $this->app->make('director')->addListener('on_before_render', function($event) {
            // must be done in an event because it must come AFTER the concrete cms package registers the
            // header navigation factory class as a singleton.
            $headerNavigationFactory = app(HeaderNavigationFactory::class);
            $headerNavigationFactory->setActiveSection(HeaderNavigationFactory::SECTION_COMMUNITY);
        });

        $this->themeRouteCollection->setThemeByRoute('/account', 'concrete_cms_theme', 'view_full.php');
        $this->themeRouteCollection->setThemeByRoute('/account/*', 'concrete_cms_theme', 'view_full.php');
    }
}
