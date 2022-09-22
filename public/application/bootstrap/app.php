<?php

/*
 * ----------------------------------------------------------------------------
 * # Custom Application Handler
 *
 * You can do a lot of things in this file.
 *
 * ## Set a theme by route:
 *
 * Route::setThemeByRoute('/login', 'greek_yogurt');
 *
 *
 * ## Register a class override.
 *
 * Core::bind('helper/feed', function() {
 * 	 return new \Application\Core\CustomFeedHelper();
 * });
 *
 * Core::bind('\Concrete\Attribute\Boolean\Controller', function($app, $params) {
 * 	return new \Application\Attribute\Boolean\Controller($params[0]);
 * });
 *
 * ## Register Events.
 *
 * Events::addListener('on_page_view', function($event) {
 * 	$page = $event->getPageObject();
 * });
 *
 *
 * ## Register some custom MVC Routes
 *
 * Route::register('/test', function() {
 * 	print 'This is a contrived example.';
 * });
 *
 * Route::register('/custom/view', '\My\Custom\Controller::view');
 * Route::register('/custom/add', '\My\Custom\Controller::add');
 *
 * ## Pass some route parameters
 *
 * Route::register('/test/{foo}/{bar}', function($foo, $bar) {
 *  print 'Here is foo: ' . $foo . ' and bar: ' . $bar;
 * });
 *
 *
 * ## Override an Asset
 *
 * use \Concrete\Core\Asset\AssetList;
 * AssetList::getInstance()
 *     ->getAsset('javascript', 'jquery')
 *     ->setAssetURL('/path/to/new/jquery.js');
 *
 * or, override an asset by providing a newer version.
 *
 * use \Concrete\Core\Asset\AssetList;
 * use \Concrete\Core\Asset\Asset;
 * $al = AssetList::getInstance();
 * $al->register(
 *   'javascript', 'jquery', 'path/to/new/jquery.js',
 *   array('version' => '2.0', 'position' => Asset::ASSET_POSITION_HEADER, 'minify' => false, 'combine' => false)
 *   );
 *
 * ----------------------------------------------------------------------------
 */

# See https://github.com/concrete5/concrete5/issues/10121
$app->extend(\Concrete\Core\Http\ServerInterface::class, fn($server) => $server->addMiddleware(
    new Class implements \Concrete\Core\Http\Middleware\MiddlewareInterface {
        public function process(
            \Symfony\Component\HttpFoundation\Request $request,
            \Concrete\Core\Http\Middleware\DelegateInterface $frame
        ) {
            return $frame->next($request);
        }
    }
));

$app->bind(\Concrete\Core\Encryption\PasswordHasher::class, \ConcreteComposer\Encryption\PasswordHasher::class);

/**
 * This event is a temporary fix for https://github.com/concretecms/concretecms/issues/10933
 * Remove when fixed in the core
 */
Events::addListener('on_user_login', function($event) {
    $db = \Database::connection();
    $user = $event->getUserObject();
    $userId = $user->getUserId();
    $nRows = $db->fetchColumn('SELECT COUNT(*) FROM authTypeConcreteCookieMap WHERE uID = ?', [$userId]);
    $maxTokens = 10;
    if ($nRows > $maxTokens) {
        $db->execute(<<<EOF
            DELETE FROM `authTypeConcreteCookieMap`
              WHERE uID = {$userId}
              AND ID <= (
                SELECT ID
                FROM (
                  SELECT ID
                  FROM `authTypeConcreteCookieMap`
                  ORDER BY ID DESC
                  LIMIT 1 OFFSET {$maxTokens}
                ) tmp
              );
            EOF
        );
    }
});
