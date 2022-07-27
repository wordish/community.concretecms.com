<?php

namespace PortlandLabs\Community\API;

use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Http\Middleware\OAuthAuthenticationMiddleware;
use Concrete\Core\Routing\RedirectResponse;
use Concrete\Core\Routing\Router;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\UserInfoRepository;
use PortlandLabs\Community\API\V1\Achievements;
use PortlandLabs\Community\API\V1\Middleware\FractalNegotiatorMiddleware;
use PortlandLabs\Community\API\V1\Profile;
use PortlandLabs\Community\API\V1\ShowcaseItems;
use PortlandLabs\Community\API\V1\Teams;
use PortlandLabs\Community\API\V1\Discourse;

class ServiceProvider extends Provider
{

    public function register()
    {
        $router = $this->app->make(Router::class);
        $router->buildGroup()
            ->setPrefix('/api/v1')
            ->addMiddleware(FractalNegotiatorMiddleware::class)
            ->routes(function ($groupRouter) {
                /**
                 * @var $groupRouter Router
                 */
                $groupRouter->post('/showcase_items/create', [ShowcaseItems::class, 'create']);
                $groupRouter->get('/showcase_items/read', [ShowcaseItems::class, 'read']);
                $groupRouter->post('/showcase_items/update', [ShowcaseItems::class, 'update']);
                $groupRouter->get('/showcase_items/delete', [ShowcaseItems::class, 'delete']);
                $groupRouter->post('/teams/search', [Teams::class, 'search']);
                $groupRouter->all('/discourse/handle_webhook_event', [Discourse::class, 'handleWebhookEvent']);
                $groupRouter->all('/discourse/edit_forum_info', [Discourse::class, 'editFormInfo']);
                $groupRouter->all('/profile/lookup_email', [Profile::class, 'lookupEmail']);

                // Handle simple redirect to profile
                $groupRouter->get('/discourse/profile/{user}', function(string $user): RedirectResponse {
                    /** @var UserInfo|null $userInfo */
                    $userInfo = $this->app->make(UserInfoRepository::class)->getByName($user);

                    // If user isn't found send to user search
                    if (!$userInfo || !$userInfo->getUserID()) {
                        return new RedirectResponse('/members/directory?q=' . urlencode($user));
                    }

                    // Otherwise redirect to user profile
                    return new RedirectResponse('/members/profile/' . (int) $userInfo->getUserID());
                });
            });

        $router->buildGroup()
            ->setPrefix('/api/v1')
            ->addMiddleware(FractalNegotiatorMiddleware::class)
            ->addMiddleware(OAuthAuthenticationMiddleware::class)
            ->routes(function ($groupRouter) {
                $groupRouter->post('/achievements/assign', [Achievements::class, 'assign']);
            });
    }
}
