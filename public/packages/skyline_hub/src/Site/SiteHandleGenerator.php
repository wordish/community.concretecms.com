<?php

namespace PortlandLabs\Skyline\Site;

use Concrete\Core\Utility\Service\Identifier;
use Concrete\Core\Utility\Service\Text;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommand;

class SiteHandleGenerator
{

    /**
     * @var Text
     */
    protected $textService;

    public function __construct(Text $textService)
    {
        $this->textService = $textService;
    }

    public function createSiteHandle(CreateHostingSiteCommand $command): string
    {
        $siteName = $command->getSiteName();
        if (strpos($siteName, 'www.') === 0) {
            $siteName = substr($siteName, 4);
        }
        $siteName = strtolower($siteName);
        $siteName = $this->textService->alphanum($siteName);
        $handle = $this->textService->shorten($siteName, 6, '');
        $handle .= '-' . strtolower(str_random(4)) . '-' . strtolower(str_random(4));
        // @todo - ensure this is unique
        return $handle;
    }

}
