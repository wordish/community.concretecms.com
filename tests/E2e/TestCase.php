<?php

namespace ConcreteComposer\E2e;

use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverHasInputDevices;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Interactions\Internal\WebDriverCoordinates;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\WebDriver\PantherWebDriverExpectedCondition;
use Symfony\Component\Serializer\Encoder\JsonDecode;
    

class TestCase extends \ConcreteComposer\TestCase
{
    use \ConcreteComposer\E2e\Traits\Auth;
    use \ConcreteComposer\E2e\Traits\Nav;
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

    protected function visibilitOfElementLocated($by)
    {
        return WebDriverExpectedCondition::visibilityOfElementLocated($by);
    }
    protected function action (WebDriverHasInputDevices $driver): WebDriverActions
    {
        return new WebDriverActions($driver);
    }

    protected function coordinates($point): WebDriverCoordinates
    {
        return new WebDriverCoordinates($point);
    }


}
