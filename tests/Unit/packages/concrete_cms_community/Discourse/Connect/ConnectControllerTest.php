<?php

use Concrete\Core\Http\Request;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\User\PostLoginLocation;
use Concrete\Core\User\User;
use Mockery as M;
use PortlandLabs\Community\Discourse\Connect\ConnectController;
use PortlandLabs\Community\Discourse\Connect\UserTransformer;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConnectControllerTest extends \PHPUnit\Framework\TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public const PASSWORD = 'testpassword';

    protected static $old;

    /**
     * @var M\Mock|ResponseFactory
     */
    private $factory;

    /**
     * @var User|M\LegacyMockInterface|M\MockInterface
     */
    private $user;

    /**
     * @var M\LegacyMockInterface|M\MockInterface|UserTransformer
     */
    private $transformer;

    /**
     * @var PostLoginLocation|M\LegacyMockInterface|M\MockInterface
     */
    private $postLogin;

    /**
     * @var ConnectController
     */
    private $controller;

    public function setUp(): void
    {
        $this->factory = M::mock(ResponseFactory::class)->makePartial();
        $this->user = M::mock(User::class);
        $this->postLogin = M::mock(PostLoginLocation::class);
        $this->transformer = M::mock(UserTransformer::class);

        $this->controller = new ConnectController($this->factory, $this->user, $this->postLogin, $this->transformer);
    }

    public function testUserLoggedIn()
    {
        [$payload, $signature] = $this->createPayload(
            [
                'nonce' => 'foo_nonce',
                'return_sso_url' => 'http://example.com',
            ]
        );

        // Make a fake request
        $request = new Request(['sig' => $signature, 'sso' => $payload]);

        // Mark the user as logged in
        $this->user->shouldReceive('checkLogin')->once()->andReturn(true);

        // Return something from our transformer
        $this->transformer->shouldReceive('transform')->once()->with($this->user)->andReturn(
            [
                'foo' => 'bar'
            ]
        );

        // Run the test
        $result = $this->controller->connect($request);

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\RedirectResponse::class, $result);
        $this->assertEquals(302, $result->getStatusCode());

        // Resolve the uri from the redirect response
        $uri = \League\Url\Url::createFromUrl($result->headers->get('location'));
        $this->assertEquals('example.com', $uri->getHost());

        $query = $uri->getQuery()->toArray();
        [$expectedPayload, $expectedSignature,] = $this->createPayload(
            [
                'foo' => 'bar',
                'nonce' => 'foo_nonce',
            ]
        );

        $this->assertEquals($expectedSignature, $query['sig'], 'Signature doesn\'t match expected!');
        $this->assertEquals($expectedPayload, $query['sso'], 'Payload doesn\'t match expected!');
    }

    public function testUserLoggedOut()
    {
        [$payload, $signature] = $this->createPayload(
            [
                'nonce' => 'foo_nonce',
                'return_sso_url' => 'http://example.com',
            ]
        );

        // Make a fake request
        $request = M::mock(Request::class);
        $request->shouldReceive('get')->andReturnValues([
            'sso' => $payload,
            'sig' => $signature
        ]);
        $currentUrl = 'http://foo.bar?sig=' . $signature . '&sso=' . $payload;
        $request->shouldReceive('getUri')->andReturn($currentUrl);

        // Mark the user as not logged in
        $this->user->shouldReceive('checkLogin')->once()->andReturn(false);

        // Make sure a post login location gets set properly
        $this->postLogin->shouldReceive('setSessionPostLoginUrl')->once()->with($currentUrl);

        // Run the test
        $result = $this->controller->connect($request);

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\RedirectResponse::class, $result);
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertEquals('/login', $result->headers->get('location'));
    }



    public function testInvalidSignature()
    {
        [$payload, $signature] = $this->createPayload(
            [
                'nonce' => 'foo_nonce',
                'return_sso_url' => 'http://example.com',
            ]
        );

        $request = new Request(['sig' => 'not valid', 'sso' => $payload]);

        // Make sure we never check if the user is logged in
        $this->user->shouldNotReceive('checkLogin');

        // Run the test
        $result = $this->controller->connect($request);

        // Make sure we got the exception we wanted
        $this->assertInstanceOf(JsonResponse::class, $result);
        $data = json_decode($result->getContent(), true);

        $this->assertSame(
            [
                'success' => false,
                'error' => [
                    'code' => 400,
                    'message' => 'Invalid signature provided.'
                ]
            ],
            $data
        );
    }

    public static function setUpBeforeClass(): void
    {
        self::$old = getenv('DISCOURSE_SECRET');
        putenv('DISCOURSE_SECRET=' . self::PASSWORD);
    }

    public static function tearDownAfterClass(): void
    {
        putenv('DISCOURSE_SECRET=' . self::$old);
    }

    private function createPayload(array $data)
    {
        $string = base64_encode((string) new \League\Url\Components\Query($data));
        return [$string, hash_hmac('sha256', $string, self::PASSWORD)];
    }

}
