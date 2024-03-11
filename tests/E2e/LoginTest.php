<?php

beforeAll(function () {
    expect(usingStage())->toBeTrue();
});

afterEach(function () {

});

beforeEach(function () {
    $this->client = $this->createPantherClient(['port' => getUnusedTcpPort()])->get($_ENV['E2E_ENDPOINT'] . '/login');
    $crawler = $this->client->getCrawler();
    $this->screenshot($this->client, 'login page');
    $greeting = $crawler->filter('div.login-wrapper h1.ccm-title');
    expect($greeting->getText())
        ->toContain('Welcome to our community');
    $this->form = $crawler->selectButton('Sign In')->form();
});

it('rejects invalid credentials', function () {
    // invalid credentials
    $this->form->setValues([
        'uName' => 'invalid' . $_ENV['E2E_ENPOINT_NONADMIN_USERNAME'],
        'uPassword' => 'invalid' . $_ENV['E2E_ENPOINT_NONADMIN_PASSWORD']
    ]);
    $this->client->submit($this->form);
    $this->screenshot($this->client, 'invalid credentials');
    $this->client->quit();
});

it('accepts valid credentials', function () {
    // valid credentials
    $this->form->setValues([
        'uName' => $_ENV['E2E_ENPOINT_NONADMIN_USERNAME'],
        'uPassword' => $_ENV['E2E_ENPOINT_NONADMIN_PASSWORD']
    ]);
    $crawler = $this->client->submit($this->form);
    $this->screenshot($this->client, 'valid credentials');
    $cssAvatar = '#ccm-desktop-nav > ul.nav.navbar-right > li:last-of-type > a';
    $avatar = $crawler->filter($cssAvatar);
    expect($avatar->attr('href'))
        ->toMatch('#/members/profile#');
    $mouseTarget = $crawler->filter($cssAvatar)->getElement(0)->getCoordinates();
    $this->client->getMouse()->mouseMove($mouseTarget);
    usleep(2 * 1000 * 1000);
    $dropdownMenu = $crawler->filter($cssAvatar . ' + ul.dropdown-menu')->getElement(0);
    $this->elementScreenshot($dropdownMenu, 'avatar menu');
    $this->client->quit();
});