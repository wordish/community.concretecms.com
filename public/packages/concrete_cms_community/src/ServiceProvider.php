<?php

declare(strict_types=1);

namespace PortlandLabs\Community;

use Concrete\Core\Application\Application;
use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Page\Theme\ThemeRouteCollection;
use Concrete\Core\User\UserInfo;
use PortlandLabs\Community\API\ServiceProvider as ApiServiceProvider;
use PortlandLabs\Community\Discourse\ServiceProvider as DiscourseProvider;
use PortlandLabs\Community\Events\DiscourseWebhookCall;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\Achievement;
use PortlandLabs\CommunityBadges\Events\AfterAssignCommunityPoints;
use PortlandLabs\CommunityBadges\Events\AfterGiveAchievement;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;
use PortlandLabs\CommunityBadges\User\Point\Action\Action;
use PortlandLabs\CommunityBadges\User\Point\Action\ActionDescription;
use PortlandLabs\ConcreteCmsTheme\Navigation\HeaderNavigationFactory;

class ServiceProvider extends Provider
{
    protected $providers = [
        DiscourseProvider::class, // Discourse connect SSO
        ApiServiceProvider::class
    ];

    /**
     * @var ThemeRouteCollection
     */
    protected $themeRouteCollection;

    public function __construct(
        Application $app,
        ThemeRouteCollection $themeRouteCollection)
    {
        parent::__construct($app);
        $this->themeRouteCollection = $themeRouteCollection;
    }

    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->make($provider)->register();
        }

        $al = AssetList::getInstance();
        $al->register("javascript", "community/teams", "js/teams.js", ["position" => Asset::ASSET_POSITION_FOOTER], "concrete_cms_community");
        $al->register("javascript", "community/karma", "js/karma.js", ["position" => Asset::ASSET_POSITION_FOOTER], "concrete_cms_community");

        $this->app->make('director')->addListener('on_before_render', function ($event) {
            // must be done in an event because it must come AFTER the concrete cms package registers the
            // header navigation factory class as a singleton.
            $headerNavigationFactory = app(HeaderNavigationFactory::class);
            $headerNavigationFactory->setActiveSection(HeaderNavigationFactory::SECTION_COMMUNITY);
        });

        $this->app->make('director')->addListener('on_discourse_webhook_call', function ($event) {
            /** @var DiscourseWebhookCall $event */

            $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
            /** @var Repository $config */
            $config = $app->make(Repository::class);
            /** @var AwardService $awardService */
            $awardService = $app->make(AwardService::class);

            if ($event->getEventName() === "topic_created") {
                $attributeKeyName = "forums_total_posts";

                $totalPostsBadgesMapping = $config->get("concrete_cms_community.badges.forums.posts_badges_mapping", [
                    1 => "post_1",
                    20 => "post_20",
                    50 => "post_50",
                    100 => "post_100",
                    200 => "post_200",
                    500 => "post_500",
                    1000 => "post_1000",
                ]);
            } else if ($event->getEventName() === "post_created") {
                $attributeKeyName = "forums_total_replies";

                if((int)$event->getPayload()["post"]["topic_posts_count"] === 1) {

                    /*
                     * Ignore this event.
                     *
                     * When you create a topic discourse fire up 2 events:
                     * topic_created + post_created
                     *
                     * We only want to process posts that are replies.
                     */

                    return;
                }

                $totalPostsBadgesMapping = $config->get("concrete_cms_community.badges.forums.replies_badges_mapping", [
                    1 => "post_reply_1"
                ]);
            } else {
                return; // ignore all other events
            }

            $userTotalPosts = (int)$event->getUser()->getUserInfoObject()->getAttribute($attributeKeyName);

            $userTotalPosts++;

            $event->getUser()->getUserInfoObject()->setAttribute($attributeKeyName, $userTotalPosts);

            ksort($totalPostsBadgesMapping);

            foreach ($totalPostsBadgesMapping as $countOfPosts => $karmaBadgeHandle) {
                if ($userTotalPosts >= $countOfPosts) {
                    try {
                        $karmaBadge = $awardService->getBadgeByHandle($karmaBadgeHandle);

                        if ($karmaBadge instanceof Achievement) {
                            $awardService->giveAchievement($karmaBadge, $event->getUser());
                        }
                    } catch (BadgeNotFound | MailTransportError |NoUserSelected | AchievementAlreadyExists $e) {
                        // Ignore all possible exceptions
                    }
                } else {
                    break;
                }
            }
        });

        $this->app->make('director')->addListener('on_after_assign_community_points', function ($event) {
            /** @var AfterAssignCommunityPoints $event */

            $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
            /** @var Connection $db */
            $db = $app->make(Connection::class);
            /** @var Repository $config */
            $config = $app->make(Repository::class);
            /** @var AwardService $awardService */
            $awardService = $app->make(AwardService::class);

            $pointsEntry = $event->getEntry();

            /** @var UserInfo $userInfo */
            $userInfo = $pointsEntry->getUserPointEntryUserObject();
            $user = $userInfo->getUserObject();

            $userTotalCommunityPoints = (int)$db->fetchColumn("SELECT SUM(upPoints) FROM UserPointHistory WHERE upuID = ?", [
                $pointsEntry->getUserPointEntryUserID()
            ]);

            $karmaPointsBadgesMapping = $config->get("concrete_cms_community.badges.karma.point_badges_mapping", [
                1000 => "karma_1000",
                5000 => "karma_5000",
                10000 => "karma_10000",
                15000 => "karma_15000",
                20000 => "karma_20000",
                25000 => "karma_25000",
                30000 => "karma_30000",
                35000 => "karma_35000",
                40000 => "karma_40000",
                45000 => "karma_45000",
                50000 => "karma_50000",
                70000 => "karma_70000",
                80000 => "karma_80000",
                90000 => "karma_90000",
                100000 => "karma_100000",
                120000 => "karma_120000",
                140000 => "karma_140000",
                160000 => "karma_160000",
                180000 => "karma_180000",
                200000 => "karma_200000"
            ]);

            ksort($karmaPointsBadgesMapping);

            foreach ($karmaPointsBadgesMapping as $karmaBadgeCommunityPoints => $karmaBadgeHandle) {
                if ($userTotalCommunityPoints >= $karmaBadgeCommunityPoints) {
                    try {
                        $karmaBadge = $awardService->getBadgeByHandle($karmaBadgeHandle);

                        if ($karmaBadge instanceof Achievement) {
                            $awardService->giveAchievement($karmaBadge, $user);
                        }
                    } catch (BadgeNotFound | MailTransportError |NoUserSelected | AchievementAlreadyExists $e) {
                        // Ignore all possible exceptions
                    }
                } else {
                    break;
                }
            }
        });


        $this->app->make('director')->addListener('on_after_give_achievement', function ($event) {
            /**
             * @var $event AfterGiveAchievement
             */
            $userBadge = $event->getUserBadge();
            if ($userBadge) {
                $user = $userBadge->getUser();
                if ($user) {
                    $wonBadgeAction = Action::getByHandle('won_badge');
                    if ($wonBadgeAction) {
                        // Let's award the user additional karma points because they won a badge.
                        $wonBadgeAction->addEntry($user, new ActionDescription());
                    }
                }
            }
        });

        $this->themeRouteCollection->setThemeByRoute('/members/profile', 'concrete_cms_theme', 'view_full.php');
        $this->themeRouteCollection->setThemeByRoute('/account', 'concrete_cms_theme', 'view_full.php');
        $this->themeRouteCollection->setThemeByRoute('/account/*', 'concrete_cms_theme', 'view_full.php');
    }
}
