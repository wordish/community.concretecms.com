<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var \Concrete\Core\Routing\Router $router
 * Base path: /ccm/system/dialogs/skyline_site
 * Namespace: Concrete\Package\SkylineHub\Controller\Search\
 */

$router->all('/advanced_search', 'AdvancedSearch::view');
$router->all('/advanced_search/add_field', 'AdvancedSearch::addField');
$router->all('/advanced_search/submit', 'AdvancedSearch::submit');
$router->all('/advanced_search/save_preset', 'AdvancedSearch::savePreset');
$router->all('/advanced_search/preset/edit', 'Preset\Edit::view');
$router->all('/advanced_search/preset/edit/edit_search_preset', 'Preset\Edit::edit_search_preset');
$router->all('/advanced_search/preset/delete', 'Preset\Delete::view');
$router->all('/advanced_search/preset/delete/remove_search_preset', 'Preset\Delete::remove_search_preset');

$router->all('/ccm/system/search/skyline_site/basic', '\Concrete\Package\SkylineHub\Controller\Search\Site::searchBasic');
$router->all('/ccm/system/search/skyline_site/current', '\Concrete\Package\SkylineHub\Controller\Search\Site::searchCurrent');
$router->all('/ccm/system/search/skyline_site/preset/{presetID}', '\Concrete\Package\SkylineHub\Controller\Search\Site::searchPreset');
$router->all('/ccm/system/search/skyline_site/clear', '\Concrete\Package\SkylineHub\Controller\Search\Site::clearSearch');
