<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\ConcreteCmsCommunity\Block\SingleBadge;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Entity\Package;
use Concrete\Core\Package\PackageService;
use PortlandLabs\CommunityBadges\AwardService;

class Controller extends BlockController
{
    protected $btTable = "btSingleBadge";

    public function getBlockTypeDescription()
    {
        return t('Adds a single badge into your page.');
    }

    public function getBlockTypeName()
    {
        return t('Single Badge');
    }

    private function getBadgeHandles()
    {
        $badgeHandles = [];

        /** @var PackageService $packageService */
        $packageService = $this->app->make(PackageService::class);
        $pkg = $packageService->getByHandle("community_badges");

        if ($pkg instanceof Package) {
            /** @var AwardService $awardService */
            $awardService = $this->app->make(AwardService::class);

            foreach ($awardService->getAllBadges() as $badge) {
                $badgeHandles[$badge->getHandle()] = $badge->getName();
            }
        }

        return $badgeHandles;
    }

    public function on_start()
    {
        $this->set("badgeHandles", $this->getBadgeHandles());
    }
}
