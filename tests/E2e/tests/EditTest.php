<?php

beforeAll(function () {
    expect(usingStage())->toBeTrue();
});

beforeEach(function () {

});

afterAll(function () {
});

afterEach(function () {
    // $this->storeSession();
});

it('can be put into edit mode', function () {
    $this->logIn();
    $this->client->get($_ENV['E2E_ENDPOINT'] . '/e2e');
    echo "getting e2e page" . PHP_EOL;
    $this->screenshot($this->client, 'e2e page');

    //    $this->client->getCrawler()->filter('#ccm-desktop-nav > ul > li:last-of-type > a')
    //        ->click();


    $crawler = $this->client->waitForVisibility('#ccm-toolbar');
    $this->screenshot($this->client, 'toolbar');
    $editToolbar = $this->client->getCrawler()
                        ->filter('#ccm-toolbar > ul > li.ccm-toolbar-page-edit > a')
                        ->getElement(0)
                        ->getCoordinates();
    $this->client->getMouse()->click($editToolbar);
    $this->screenshot($this->client, 'click edit');

    $click_proxy = '#ccm-menu-click-proxy';
    $crawler = $this->client->waitFor($click_proxy);
    $mouse_target = $crawler->filter('div.ccm-block-edit')->getElement(0)->getCoordinates();
    $mouse = $this->client->getMouse();

    /* $mouse_moved = $mouse->mouseMove($mouse_target);
     * $this->screenshot($this->client, 'mouse move');
     * $mouse_moved = $mouse->mouseMove($mouse_target, -50, 0);
     * $this->screenshot($this->client, 'mouse move 2'); */

    // $mouse_moved->click();

    $action = $this->action($this->client);
    echo "action\n";
    $action->moveToElement(
        $crawler->filter('#area-menu-footer-522')->getElement(0),
    );
    echo "move to element\n";
    $this->screenshot($this->client, 'move to element');
    // $action->click();
    $action->perform();
    echo "perform\n";
    $this->screenshot($this->client, 'perform');

    $action->click();
    echo "click\n";
    $this->screenshot($this->client, 'click');

    $action->perform();
    echo "perform\n";
    $this->screenshot($this->client, 'perform 2');

    $action->moveToElement(
        $crawler->filter('div.ccm-block-edit')->getElement(0),
        -100,
        0
    );
    echo "move to element -100\n";
    $this->screenshot($this->client, 'move to element -100');

    $action->perform();
    echo "perform\n";
    $this->screenshot($this->client, 'perform 3');

    $action->click();
    echo "click\n";
    $this->screenshot($this->client, 'click 2');


    // $mouse->mouseMove($click_proxy, -10,-10);
    // $mouse->click($this->client->getMouse()->getCurrentPosition());
    $this->screenshot($this->client, 'click edit block');
    // $mouse->mouseMove('div.ccm-block-edit')->click();
    // $this->screenshot($this->client, 'move to and click edit block');
    // $crawler->filter()->getElement(0)->click();
    $this->client->wait(2);
    $this->screenshot($this->client, 'click edit block after waiting');

    $this->client->wait(10, 1000)->until(
        $this->visibilitOfElementLocated(\Facebook\WebDriver\WebDriverBy::cssSelector('#ccm-popover-menu-container div.ccm-edit-mode-block-menu div.dropdown-menu'))
    );
    $this->screenshot($this->client, 'wait after click edit');
    $crawler = $this->client->waitFor('#ccm-popover-menu-container div.ccm-edit-mode-block-menu div.dropdown-menu');
    $this->screenshot($this->client, 'menu container');
    $editInline = $crawler
       ->filter('#ccm-popover-menu-container > div.ccm-edit-mode-block-menu > div.popover-inner > div.dropdown-menu > a[data-menu-action="edit_inline"]')
       ->getElement(0)
       ->getCoordinates();
    $mouse->click($editInline);
    $this->screenshot($this->client, 'clicke menu container');
    $this->client->waitForVisibility('div[id^="cke_cke-"]');
    $this->screenshot($this->client);
    $this->client->quit();
});
