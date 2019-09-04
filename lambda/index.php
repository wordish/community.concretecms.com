<?php

use Concrete\Core\Application\Application;
use Concrete5\Community\Lambda\ServiceProvider;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

define('C5_EXECUTE', 'lambda');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../public/application/bootstrap/autoload.php';

// Define c5 constants
const DIR_CONFIG_SITE = __DIR__ . '/../public/application/config';
const DIRECTORY_PERMISSIONS_MODE_COMPUTED = '0777';
const FILE_PERMISSIONS_MODE_COMPUTED = '0777';
const ASSETS_URL_IMAGES = '';

require_once __DIR__ . '/../public/concrete/bootstrap/configure.php';
require_once __DIR__ . '/../public/concrete/bootstrap/paths.php';

// Load up a container
$app = new Application();
$app->instance(Application::class, $app);
$app->make(ServiceProvider::class)->register();

// Declare routes in a simple format: `class::method`
$routes = [
    '/github/webhook' => '\Concrete5\Community\Lambda\Http\Controller\Github::handleWebhook'
];

// Handle dispatching
$response = null;
$request = ServerRequest::fromGlobals();
if ($route = $routes[$request->getRequestTarget()] ?? null) {
    [$class, $method] = explode('::', $route);

    // Dispatch
    $response = $app->call([$app->make($class), $method], [
        'request' => $request
    ]);
} else {
    // Handle 404
    $response = new Response(404, [], 'Not Found.');
}

// Negotiate different types of results into a response
if (is_string($response)) {
    $response = ['message' => $response];
}

if (is_array($response)) {
    $response = new Response(200, [], json_encode($response));
}

// Handle an invalid response
if (!$response instanceof ResponseInterface) {
    $response = new Response(500, [], 'An unexpected error occurred.');
}

// Emit the response
$emitter = new SapiEmitter();
$emitter->emit($response);
