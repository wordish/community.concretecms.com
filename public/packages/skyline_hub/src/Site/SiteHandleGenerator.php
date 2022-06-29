<?php

namespace PortlandLabs\Skyline\Site;

use Badcow\PhraseGenerator\PhraseGenerator;
use Concrete\Core\Utility\Service\Text;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommand;

class SiteHandleGenerator
{

    /**
     * @var Text
     */
    protected $textService;

    /**
     * @var PhraseGenerator
     */
    protected $phraseGenerator;

    /**
     * SiteHandleGenerator constructor.
     * @param Text $textService
     * @param PhraseGenerator $phraseGenerator
     */
    public function __construct(Text $textService, PhraseGenerator $phraseGenerator)
    {
        $this->textService = $textService;
        $this->phraseGenerator = $phraseGenerator;
    }


    public function createSiteHandle(CreateHostingSiteCommand $command): string
    {
        $siteName = $command->getSiteName();
        if (strpos($siteName, 'www.') === 0) {
            $siteName = substr($siteName, 4);
        }
        $handle = $this->textService->shorten($this->textService->asciify($siteName), 12, '');
        $handle .= $this->phraseGenerator->generate(2, 1);
        $handle = kebab_case($handle);
        return $handle;
    }

}
