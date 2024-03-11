<?php


it('shows stage banner', function () {
    expect(usingStage())->toBeTrue();

    $client = $this->createPantherClient()->get($_ENV['E2E_ENDPOINT']);
    $crawler = $client->waitForVisibility('.alert.alert-danger');

    $alert = $crawler->filter('body > .alert.alert-danger');
    expect($alert)->getText()->toBe('STAGE');
    $this->elementScreenshot($alert->getElement(0), 'banner');
});

it('shows working cookie banner', function () {
    $client = $this->createPantherClient()->get($_ENV['E2E_ENDPOINT']);

    $crawler = $client->waitFor('div.disclosure > div > p');
    // Wait for cookie banner to show. Need an extra 2 seconds to accommodate transition
    usleep(2*1000*1000);
    $this->elementScreenshot($crawler->filter('div.disclosure')->getElement(0), 'banner');

    // Verify the banner shows as expected with our privacy policy
    $spiel = $crawler->filter('div.disclosure p');
    $this->elementScreenshot($spiel->getElement(0), 'spiel');
    $button = $crawler->filter('button.ack');
    expect($spiel->getText())
        ->toContain('This website stores cookies on your computer')
        ->toContain('Privacy Policy')
        ->and($spiel->selectLink('Privacy Policy'))
        ->getAttribute('href')->toBe('https://www.concretecms.com/about/legal/privacy-policy')
        ->and($button)->count()->toBe(1);

    $totalCookies = count($client->getCookieJar()->all());

    // Click the accept button
    $client->executeScript("document.querySelector('div.disclosure button.ack').click()");

    // Expect the banner to go away
    $client->waitForInvisibility('div.disclosure');

    // Expect to have an additional cookie now
    expect($client->getCookieJar())
        ->all()->not->toHaveCount($totalCookies)
        ->get('ccm_cdd')->not->toBeNull();
    ;

    $this->screenshot($client);
});
