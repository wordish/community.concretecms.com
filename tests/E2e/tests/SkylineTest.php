<?php

beforeAll(function () {
    expect(usingStage())->toBeTrue();
});

it('shows the form to create a new site for an authenticated user', function () {

    /**
    $this->client = $this->createPantherClient()->get('https://duckduckgo.com');
    $this->client->findElement($this->byCss('input[name="q"]'))->sendKeys('test');
    $this->client->findElement($this->byCss('button[type="submit"]'))->click();
    $this->client->wait()->until(\Facebook\WebDriver\WebDriverExpectedCondition::titleContains('test'));
    $title = $this->client->getTitle();
    $this->screenshot($this->client, 'duck duck go');
    echo "Found : {$title}" . PHP_EOL;
    expect($title)->toContain('test');
    return;
     */

    $this->logIn('guest');
    $this->screenshot($this->client, 'logged in');

    echo 'going to form' . PHP_EOL;
    $this->goTo('/get-concrete-site');
    $this->screenshot($this->client, 'go to form');

    $crawler = $this->client->waitFor('div > form');

    expect($crawler->filter('div.create-site-form p.text-center a')->getElement(0)->getText())
        ->toContain('Log in as someone else');

    // Field that exists when not logged in used to create an account
    expect($crawler->filter('#register-email')->getElement(0))->toBeNull();

    $form = $crawler->filter('div.create-site-form form input')->getElement(0);
    $form->sendKeys('E2E test ' . (new DateTime())->format("Y-m-d\TH:i:s"));

    echo 'completed form' . PHP_EOL;
    $this->screenshot($this->client, 'form complete');

    $button = $this->ByCss('form button');

//    $this->client->findElement($button)->click();
    $this->client->executeScript('document.querySelector(\'form button\').click()');
    $this->screenshot($this->client, 'button clicked');
    $this->client->wait()->until(\Facebook\WebDriver\WebDriverExpectedCondition::titleContains('View Site'));
//    usleep(5*2^20);
//    $this->client
//        ->wait()
//        ->until(\Facebook\WebDriver\WebDriverExpectedCondition::urlContains('account'));
    echo 'submitted form' . PHP_EOL;
    $this->screenshot($this->client, 'form submitted');

//    $action = new \Facebook\WebDriver\Interactions\WebDriverActions($this->client);
//
//    $action->click($crawler->filter('form button')->getElement(0));
//    $action->release($crawler->filter('form button')->getElement(0));
//    $action->perform();
//    $form->findElement($this->ByCss('button'))->click();

//    $action->perform();
//    $action->release()->perform();
//    echo 'submitted form' . PHP_EOL;
//    $this->screenshot($this->client, 'form submitted');

    $h1 = 'div.page-header > h1';
    $this->client->waitFor($h1);
    expect($crawler->filter($h1)->getElement(0)->getText())
        ->toContain('Installing Concrete');
    $this->screenshot($this->client, 'h1');

    $card = 'div.card div.ep-container div.ep-legend--container';
    $this->client->waitFor($card);
    expect($crawler->filter($container)->getText())
        ->toContain(' LOADING');
    $this->screenshot($this->client, 'loading');

});
