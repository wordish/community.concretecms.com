<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

// Set up E2E tests with environment

uses(\ConcreteComposer\E2e\TestCase::class)
    ->group('e2e')
    ->beforeAll(function () {
        // Load .env
        $env = new \Dotenv\Dotenv(__DIR__ . '/../');
        try {
            $env->overload();
        } catch (\Exception $e) {
            // Ignore any errors
        }

        $requiredEnv = ['E2E_ENDPOINT'];
        foreach ($requiredEnv as $item) {
            assert(isset($_ENV[$item]), '$_ENV["' . $item . '"] is required.');
        }
    })
    ->in('E2e');
uses(\ConcreteComposer\TestCase::class)->group('unit')->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeTen', function () {
    return $this->toBe(10);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/
function getUnusedTcpPort ()
{
    $address = '127.0.0.1';
    // Create a new socket
    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    // Bind the source address
    socket_bind($sock, $address);
    socket_getsockname($sock, $address, $port);
    socket_close($sock);
    return $port;
}

function usingStage ()
{
    if (!$_ENV['E2E_DEBUG']) {
        return true;
    }
    if (!str_contains($_ENV['E2E_ENDPOINT'], '.stage.')) {
        $this->markTestSkipped('E2E endpoint doesn\'t look like a stage.');
    }
    return true;
}

function chromeConfig ()
{
    return [
        [], //'port' => getUnusedTcpPort()],
        [],
        [
            'chromedriver_arguments' => [
                '--disable-background-networking',
                '--disable-default-apps',
                '--disable-dev-shm-usage',
                '--disable-extensions',
                '--disable-gpu',
                '--disable-prompt-on-repost',
                '--disable-sync',
                '--disable-translate',
                '--incognito',
                '--log-level=DEBUG',
                '--log-path=/tmp/chrome.log',
                '--mute-audio',
                '--no-first-run',
                '--no-sandbox',
                '--remote-debugging-port=9222',
                '--window-size=1920,1080'
            ],

        ]
    ];
}
