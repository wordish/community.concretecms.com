<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */
/** @noinspection PhpUnused */

namespace Concrete\Package\SkylineHub\Controller\Dialog\Site;

use Concrete\Controller\Dialog\Search\AdvancedSearch as AdvancedSearchController;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Search\Field\ManagerFactory;
use Concrete\Core\Permission\Key\Key;
use Concrete\Core\Entity\Search\SavedSearch;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Search\SavedSiteSearch;
use PortlandLabs\Skyline\Search\Site\SearchProvider;

class AdvancedSearch extends AdvancedSearchController
{
    protected $supportsSavedSearch = true;
    
    protected function canAccess()
    {
        $permissionKey = Key::getByHandle("read_skyline_site_entries");
        return $permissionKey->validate();
    }
    
    public function on_before_render()
    {
        parent::on_before_render();
        
        // use core views (remove package handle)
        $viewObject = $this->getViewObject();
        $viewObject->setInnerContentFile(null);
        $viewObject->setPackageHandle(null);
        $viewObject->setupRender();
    }
    
    public function getSearchProvider()
    {
        return $this->app->make(SearchProvider::class);
    }
    
    public function getSavedSearchEntity()
    {
        $em = $this->app->make(EntityManager::class);
        if (is_object($em)) {
            return $em->getRepository(SavedSiteSearch::class);
        }
        
        return null;
    }
    
    public function getFieldManager()
    {
        return ManagerFactory::get('skyline_site');
    }
    
    public function getCurrentSearchBaseURL()
    {
        return Url::to('/ccm/system/search/skyline_site/current');
    }
    
    public function getSearchPresets()
    {
        $em = $this->app->make(EntityManager::class);
        if (is_object($em)) {
            return $em->getRepository(SavedSiteSearch::class)->findAll();
        }
    }
    
    public function getSubmitMethod()
    {
        return 'get';
    }
    
    public function getSubmitAction()
    {
        return $this->app->make('url')->to('/dashboard/skyline/sites', 'advanced_search');
    }
    
    public function getSavedSearchBaseURL(SavedSearch $search)
    {
        return $this->app->make('url')->to('/dashboard/skyline/sites', 'preset', $search->getID());
    }
    
    public function getBasicSearchBaseURL()
    {
        return Url::to('/ccm/system/search/skyline_site/basic');
    }
    
    public function getSavedSearchDeleteURL(SavedSearch $search)
    {
        return (string) Url::to('/ccm/system/dialogs/skyline_site/advanced_search/preset/delete?presetID=' . $search->getID());
    }
    
    public function getSavedSearchEditURL(SavedSearch $search)
    {
        return (string) Url::to('/ccm/system/dialogs/skyline_site/advanced_search/preset/edit?presetID=' . $search->getID());
    }
}
