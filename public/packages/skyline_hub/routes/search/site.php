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
 * Base path: /ccm/system/search/skyline_site
 * Namespace: Concrete\Package\SkylineHub\Controller\Search\
 */

$router->all('/basic', 'Site::searchBasic');
$router->all('/current', 'Site::searchCurrent');
$router->all('/preset/{presetID}', 'Site::searchPreset');
$router->all('/clear', 'Site::clearSearch');
