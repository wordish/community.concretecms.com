<?php

namespace Concrete5\Community\Integration\Stackoverflow;

use Concrete5\Community\TestCase;
use Mockery;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Client\StreamClient;
use OAuth\Common\Storage\SymfonySession;
use ReflectionClass;
use ReflectionMethod;

class StackoverflowServiceTest extends TestCase
{

    /**
     * @var StackoverflowServiceAlias
     */
    protected $service;

    /**
     * @before
     */
    public function setup(): void
    {
        $credentials = Mockery::mock(Credentials::class);
        $client = Mockery::mock(StreamClient::class);
        $storage = Mockery::mock(SymfonySession::class);

        $this->service = new StackoverflowService($credentials, $client, $storage);
    }

    public function testGetAccessTokenEndpoint(): void
    {
        $this->assertEquals(
            'https://stackoverflow.com/oauth/access_token',
            (string) $this->service->getAccessTokenEndpoint()
        );
    }

    public function testGetAuthorizationMethod(): void
    {
        $this->assertEquals(
            $this->service::AUTHORIZATION_METHOD_QUERY_STRING,
            $this->service->getAuthorizationMethod()
        );
    }

    public function testGetAuthorizationEndpoint(): void
    {
        $this->assertEquals('https://stackoverflow.com/oauth', (string) $this->service->getAuthorizationEndpoint());
    }

    public function testParseAccessTokenResponse(): void
    {
        $reflection = new ReflectionMethod(StackoverflowService::class, 'parseAccessTokenResponse');
        $reflection->setAccessible(true);

        /** @var \OAuth\OAuth2\Token\TokenInterface $token */
        $token = $reflection->invoke($this->service, 'access_token=foobar');
        $this->assertEquals('foobar', $token->getAccesstoken());
        $this->assertEquals($token::EOL_NEVER_EXPIRES, $token->getEndOfLife());
    }
}
