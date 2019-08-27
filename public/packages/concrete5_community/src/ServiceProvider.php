<?php

namespace Concrete5\Community;

use Concrete\Core\Application\Application;
use Concrete\Core\Config\Repository\Liaison;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;
use Concrete5\Community\Integration\Linkedin\LinkedinService;
use Concrete5\Community\Integration\Stackoverflow\StackoverflowService;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Storage\SymfonySession;
use OAuth\OAuth2\Service\Linkedin;
use OAuth\ServiceFactory;

class ServiceProvider extends Provider
{

    /**
     * @var \Concrete\Core\Config\Repository\Repository
     */
    private $config;

    /**
     * @var \Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface
     */
    private $urlResolver;

    public function __construct(Application $app, Repository $config, ResolverManagerInterface $urlResolver)
    {
        parent::__construct($app);
        $this->config = new Liaison($config, 'concrete5_community');
        $this->urlResolver = $urlResolver;
    }

    /**
     * A list of the classes this service provides for
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            StackoverflowService::class,
            ServiceFactory::class,
        ];
    }

    /**
     * Registers the services provided by this provider.
     */
    public function register(): void
    {
        // Extend the OAuth service factory so that we can use it to build our service
        $this->app->extend(ServiceFactory::class, static function (ServiceFactory $factory) {
            return $factory
                ->registerService('stackoverflow', StackoverflowService::class)
                ->registerService('linkedin', LinkedinService::class);
        });

        // Set up the routine for building stackoverflow
        $this->app->bind(StackoverflowService::class, function (Application $app) {
            ['id' => $key, 'secret' => $secret] = $this->config->get('integrations.stackoverflow');
            $credentials = new Credentials(
                $key,
                $secret,
                (string) $this->urlResolver->resolve(['/account/stackoverflow/handle_auth_response'])
            );
            $storage = new SymfonySession($app->make('session'), false);
            $baseUri = new Uri('https://api.stackexchange.com/2.2/');

            return $app
                ->make(ServiceFactory::class)
                ->createService('stackoverflow', $credentials, $storage, ['no_expiry'], $baseUri);
        });

        // Set up the routine for building our linkedin service
        $this->app->bind(Linkedin::class, function (Application $app) {
            ['id' => $key, 'secret' => $secret] = $this->config->get('integrations.linkedin');
            $credentials = new Credentials(
                $key,
                $secret,
                (string) $this->urlResolver->resolve(['/account/linkedin/handle_auth_response'])
            );
            $storage = new SymfonySession($app->make('session'), false);
            $baseUri = new Uri('https://api.linkedin.com/v2/');

            return $app
                ->make(ServiceFactory::class)
                ->createService('linkedin', $credentials, $storage, [LinkedinService::SCOPE_R_LITEPROFILE], $baseUri);
        });
    }
}
