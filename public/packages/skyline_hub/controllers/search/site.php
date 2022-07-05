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

namespace Concrete\Package\SkylineHub\Controller\Search;

use PortlandLabs\Skyline\Entity\Search\SavedSiteSearch;
use Concrete\Core\Search\Field\Field\KeywordsField;
use Doctrine\ORM\EntityManagerInterface;
use Concrete\Controller\Search\Standard;
use Concrete\Core\Permission\Key\Key;
use Concrete\Package\SkylineHub\Controller\Dialog\Site\AdvancedSearch;

class Site extends Standard
{
    /**
     * @return \Concrete\Controller\Dialog\Search\AdvancedSearch
     */
    protected function getAdvancedSearchDialogController()
    {
        return $this->app->make(AdvancedSearch::class);
    }
    
    /**
     * @param int $presetID
     *
     * @return SavedSiteSearch|null
     */
    protected function getSavedSearchPreset($presetID)
    {
        $em = $this->app->make(EntityManagerInterface::class);
        return $em->find(SavedSiteSearch::class, $presetID);
    }
    
    /**
     * @return KeywordsField[]
     */
    protected function getBasicSearchFieldsFromRequest()
    {
        $fields = parent::getBasicSearchFieldsFromRequest();
        $keywords = htmlentities($this->request->get('cKeywords'), ENT_QUOTES, APP_CHARSET);
        if ($keywords) {
            $fields[] = new KeywordsField($keywords);
        }
        
        return $fields;
    }
    
    /**
     * @return bool
     */
    protected function canAccess()
    {
        $permissionKey = Key::getByHandle("read_hosting_site_entries");
        return $permissionKey->validate();
    }
}
