<?php

namespace ConcreteComposer\E2e\Traits;

use Facebook\WebDriver\WebDriverBy;

trait Nav
{
    public function goTo ($path): void
    {
        $this->client->get($_ENV['E2E_ENDPOINT'] . $path);
        echo "navigated to " . $path . PHP_EOL;
        return;

    }

    protected function byCss ($css): WebDriverBy
    {
        return WebDriverBy::cssSelector($css);
    }

    protected function byTagName ($tag_name): WebDriverBy
    {
        return WebDriverBy::tagName($tag_name);
    }
}
