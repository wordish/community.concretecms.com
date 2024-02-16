<?php
declare(strict_types=1);

// Load in composer
use Concrete\Core\Support\Facade\Application;

require_once __DIR__ . '/../vendor/autoload.php';

// Load in concrete5 constants
require_once __DIR__ . '/../public/concrete/bootstrap/configure.php';

// Load aliases because some core classes extend them
$config = require __DIR__ . '/../public/concrete/config/app.php';
$aliases = array_get($config, 'aliases', []);
$list = new \Concrete\Core\Foundation\ClassAliasList();
$list->registerMultiple($aliases);

// Register an autoloader for those aliases
$autoloader = new \Concrete\Core\Foundation\AliasClassLoader($list);
$autoloader->register();

// Register a new autoloader for connect package
$loader = new \Concrete\Core\Foundation\Psr4ClassLoader();
$loader->addPrefix('PortlandLabs\Community', __DIR__ . '/../public/packages/concrete_cms_community/src');
$loader->register();
