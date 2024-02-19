<?php

namespace ConcreteComposer\E2e;

use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverElement;
use Symfony\Component\Panther\Client;

class TestCase extends \ConcreteComposer\TestCase
{
    use \Symfony\Component\Panther\PantherTestCaseTrait;

    protected function screenshot(Client $client, string $action = ''): void
    {
        $name = snake_case($this->getName());
        if ($action !== '') {
            $name .= '_' . $action;
        }
        $client->takeScreenshot(__DIR__ . '/../proof/' . $name . '.png');
    }

    protected function elementScreenshot(?WebDriverElement $element, string $action): void
    {
        if ($element instanceof RemoteWebElement) {
            $name = snake_case($this->getName());
            if ($action !== '') {
                $name .= '/' . $action;
            }
            $element->takeElementScreenshot(__DIR__ . '/../proof/' . $name . '.png');
        }
    }
}