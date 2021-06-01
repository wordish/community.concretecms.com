<?php

namespace Concrete\Package\ConcreteCmsCommunity;

use Concrete\Core\Application\UserInterface\Dashboard\Navigation\FavoritesNavigationCache;
use Concrete\Core\Application\UserInterface\Dashboard\Navigation\NavigationCache;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Tree\Node\Type\GroupFolder;
use Concrete\Core\Tree\Type\Group as GroupTree;
use Concrete\Core\User\Group\GroupRole;
use Concrete\Core\User\Group\GroupType;
use PortlandLabs\Community\Console\Command\AssignAnniversaryBadges;
use PortlandLabs\Community\ServiceProvider;
use PortlandLabs\Community\TeamsService;

class Controller extends Package
{

    protected $pkgHandle = 'concrete_cms_community';
    protected $appVersionRequired = '9.0.0a1';
    protected $pkgVersion = '0.90';
    protected $pkgAutoloaderMapCoreExtensions = true;
    protected $pkgAutoloaderRegistries = array(
        'src' => '\PortlandLabs\Community'
    );

    public function getPackageDescription()
    {
        return t("The community.concretecms.com extensions.");
    }

    public function getPackageName()
    {
        return t("community.concretecms.com");
    }

    private function configureTeamsFunctionality()
    {
        $groupFolderName = t("Community Teams");
        $groupTypeName = t("Community Team");

        $app = Application::getFacadeApplication();
        /** @var TeamsService $teamService */
        $teamService = $app->make(TeamsService::class);

        // @todo: in the future a handle for group types would be nice to have

        if (!in_array($groupTypeName, GroupType::getSelectList())) {
            // setup the teams group type
            $groupType = GroupType::add($groupTypeName, false);

            // create the member role
            $memberRole = GroupRole::add(t("Member"), false);
            $groupType->addRole($memberRole);
            $groupType->setDefaultRole($memberRole);

            // create the manager role
            $managerRole = GroupRole::add(t("Manager"), true);
            $groupType->addRole($managerRole);

            $teamService->setTeamsGroupType($groupType);

            // setup the teams root node
            $groupFolder = GroupFolder::add($groupFolderName, GroupTree::get()->getRootTreeNodeObject(), GroupFolder::CONTAINS_SPECIFIC_GROUPS, [$groupType]);
            $teamService->setTeamsGroupFolder($groupFolder);
        }
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installContentFile('data.xml');
        $this->installContentFile('content.xml');

        Page::getByPath('/members/profile')->delete();
        Single::add('/members/profile', $pkg)->update(['cName' => 'View Profile']);

        $this->configureTeamsFunctionality();
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile('data.xml');

        $this->configureTeamsFunctionality();

        // Clear the cache to prevent navigation issues
        /** @var NavigationCache $navigationCache */
        $navigationCache = $this->app->make(NavigationCache::class);
        $navigationCache->clear();
        $navigationCache = $this->app->make(FavoritesNavigationCache::class);
        $navigationCache->clear();
    }


    public function on_start()
    {
        // Register our service providers
        $this->app->make(ServiceProvider::class)->register();

        if ($this->app->isRunThroughCommandLineInterface()) {
            $console = $this->app->make('console');
            $console->add(new AssignAnniversaryBadges());
        }
    }
}
