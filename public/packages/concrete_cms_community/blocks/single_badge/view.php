<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Entity\File\File;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\Entity\Package;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\User\User;
use Concrete\Package\CommunityBadges\Controller;
use HtmlObject\Image;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\Badge;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;

/** @var string $badgeHandle */

$app = Application::getFacadeApplication();
/** @var PackageService $packageService */
$packageService = $app->make(PackageService::class);
$pkg = $packageService->getByHandle("community_badges");
$user = new User();

?>

<?php if ($pkg instanceof Package) { ?>
    <div class="profile-badge <?php echo !$user->isRegistered() ? "transparent" : "";?>">
        <?php

        /** @var Controller $pkgController */
        $pkgController = $pkg->getController();
        $badgeUrl = $pkgController->getRelativePath() . "/images/default_badge.png";
        $badgeName = t("Unknown Badge");

        /** @var AwardService $awardService */
        $awardService = $app->make(AwardService::class);

        try {
            $badge = $awardService->getBadgeByHandle($badgeHandle);

            if ($badge instanceof Badge) {
                $badgeThumbnail = $badge->getThumbnail();

                if ($badgeThumbnail instanceof File) {
                    $badgeThumbnailVersion = $badgeThumbnail->getApprovedVersion();
                    if ($badgeThumbnailVersion instanceof Version) {
                        $badgeUrl = $badgeThumbnailVersion->getURL();
                    }
                }

                $badgeName = $badge->getName();
            }
        } catch (BadgeNotFound $e) {
            // Ignore if the badge was not found we have already fallbacks defined...
        }

        echo new Image($badgeUrl, $badgeName);

        ?>
    </div>
<?php } ?>