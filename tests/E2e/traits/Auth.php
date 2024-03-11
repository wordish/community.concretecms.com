<?php

namespace ConcreteComposer\E2e\Traits;

trait Auth
{
    public function logIn (string $user_type = null): void
    {
        $this->client = $this->createPantherClient(...chromeConfig()); // ->get($_ENV['E2E_ENDPOINT'] . '/login');
        $this->client->get($_ENV['E2E_ENDPOINT']);
        $this->screenshot($this->client, 'create client');
        
        if (!$this->restoreSession($this->client)) {
            echo "logging in\n";
            switch ($user_type) {
                case 'editor':
                    $type = 'EDITOR';
                case 'guest':
                default:
                    $type = 'GUEST';
            }
            $this->client->get($_ENV['E2E_ENDPOINT'] . '/login');
            $crawler = $this->client->getCrawler();
            $this->screenshot($this->client, 'login page');
            $greeting = $crawler->filter('div.login-wrapper h1.ccm-title');
            expect($greeting->getText())
                ->toContain('Welcome to our community');
            $this->form = $crawler->selectButton('Sign In')->form();
            $this->form->setValues([
                'uName' => $_ENV["E2E_ENDPOINT_{$type}_USERNAME"],
                'uPassword' => $_ENV["E2E_ENDPOINT_{$type}_PASSWORD"]
            ]);
            $crawler = $this->client->submit($this->form);
            $this->screenshot($this->client, 'log in');
            echo "logged in\n";
            $this->storeSession();
            return;
        }
        $cookies_restored = $this->client->getCookieJar()->all();
        array_walk($cookies_restored, function ($el) { echo "Client has: " . implode('; ', [$el->getDomain(), $el->getPath(),$el->getName()]) . PHP_EOL; return $el; });
        $this->client->get($_ENV['E2E_ENDPOINT']);
        $this->screenshot($this->client, 'session restored');
        echo "already logged in\n";
        return;

    }
    
    protected function storeSession (): void
    {
        /** @var Client $client */
        $client = $this->client;
        /** @var \Symfony\Component\BrowserKit\CookieJar */
        $cookie_jar = $client->getCookieJar();
        $encoded_cookies = serialize($cookie_jar->all());
        file_put_contents('./cookies.txt', $encoded_cookies);
        echo "session stored" . PHP_EOL;
    }

    protected function restoreSession ($client): bool
    {
        $cookie_file = './cookies.txt';
        if (!file_exists($cookie_file)) {
            echo "no session to restore\n";
            return false;
        }
        $client->getCookieJar()->expire('CONCRETE', '/', preg_replace('/^.*?([a-z-]+(\.[a-z-]+){3})$/', '$1', $_ENV['E2E_ENDPOINT']));
        var_dump($client->getCookieJar()->all());
        foreach (unserialize(file_get_contents($cookie_file)) as $cookie) {
            echo "Restored \"" . $cookie->getName() . "\" cookie" . PHP_EOL;
            $client->getCookieJar()->set($cookie);
        }
        echo "restoring session\n";
        return true;
    }
}
