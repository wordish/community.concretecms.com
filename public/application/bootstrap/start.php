<?php

use Concrete\Core\Application\Application;

// Override the user class completely
require_once __DIR__ . '/../src/User/User.php';

/*
 * ----------------------------------------------------------------------------
 * Instantiate concrete5
 * ----------------------------------------------------------------------------
 */
$app = new Application();

/*
 * ----------------------------------------------------------------------------
 * Detect the environment based on the hostname of the server
 * ----------------------------------------------------------------------------
 */
$app->detectEnvironment(
    array(
        'local' => array(
            'hostname',
        ),
        'production' => array(
            'live.site',
        ),
    ));

return $app;
