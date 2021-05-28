<?php

/**
 * @project:   Community Badges
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\Community\Console\Command;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\UserList;
use PortlandLabs\CommunityBadges\AwardService;
use PortlandLabs\CommunityBadges\Entity\Achievement;
use PortlandLabs\CommunityBadges\Exceptions\AchievementAlreadyExists;
use PortlandLabs\CommunityBadges\Exceptions\BadgeNotFound;
use PortlandLabs\CommunityBadges\Exceptions\MailTransportError;
use PortlandLabs\CommunityBadges\Exceptions\NoUserSelected;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Concrete\Core\Support\Facade\Application;
use Symfony\Component\Console\Style\SymfonyStyle;
use DateTime;
use DateInterval;

/** @noinspection PhpUnused */

class AssignAnniversaryBadges extends Command
{
    protected function configure()
    {
        $this
            ->setName('concrete-cms-community:assign-anniversary-badges')
            ->setDescription(t('Assign anniversary badges to all users of the site.'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $app = Application::getFacadeApplication();
        /** @var Repository $config */
        $config = $app->make(Repository::class);
        /** @var AwardService $awardService */
        $awardService = $app->make(AwardService::class);

        /*
         * Build a mapping array with all badges for the years 1 to 16.
         */

        $maxYears = (int)$config->get("concrete_cms_community.badges.anniversary.total_years", 16);

        $anniversaryBadges = [];

        for ($yearsCounter = 1; $yearsCounter <= $maxYears; $yearsCounter++) {
            try {
                $badgeHandle = "anniversary_" . (string)$yearsCounter;

                $anniversaryBadge = $awardService->getBadgeByHandle($badgeHandle);
                $anniversaryBadges[$yearsCounter] = $anniversaryBadge;
            } catch (BadgeNotFound $e) {
                $io->warning(
                    sprintf(
                        "There is no badge defined for year %s. Please create a badge with the handle \"%s\".",
                        (string)$yearsCounter,
                        $badgeHandle
                    )
                );
            }
        }

        /*
         * Now let's assign the badges to the users...
         */

        $userList = new UserList();

        $userList->ignorePermissions();

        $currentDate = new DateTime();

        foreach ($userList->getResults() as $currentUserInfo) {
            if ($currentUserInfo instanceof UserInfo) {
                $currentUser = $currentUserInfo->getUserObject();

                $currentUserDateAdded = $currentUserInfo->getUserDateAdded();

                if ($currentUserDateAdded instanceof DateTime) {
                    $dateInterval = $currentDate->diff($currentUserDateAdded);

                    if ($dateInterval instanceof DateInterval) {
                        $currentUsersTotalYears = $dateInterval->y;

                        /*
                         * We go through all years for each users.
                         *
                         * This has may a lower performance but so we can also assign multiple badges when migrating users
                         * from the old concrete5.org site.
                         */

                        for ($yearsCounter = 1; $yearsCounter <= $maxYears; $yearsCounter++) {
                            if ($currentUsersTotalYears >= $yearsCounter) {
                                $anniversaryBadge = $anniversaryBadges[$yearsCounter];

                                if ($anniversaryBadge instanceof Achievement) {
                                    try {
                                        $awardService->giveAchievement($anniversaryBadge, $currentUser);

                                        $io->success(
                                            sprintf(
                                                "Assign %s anniversary badge to user %s.",
                                                $yearsCounter,
                                                $currentUserInfo->getUserName()
                                            )
                                        );

                                    } catch (AchievementAlreadyExists $e) {
                                        // The user already got the anniversary badge.
                                    } catch (MailTransportError | NoUserSelected $e) {
                                        $io->error(
                                            sprintf(
                                                "There was en error while assigning the user %s the %s anniversary badge.",
                                                $currentUserInfo->getUserName(),
                                                $yearsCounter
                                            )
                                        );
                                    }
                                }
                            } else {
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
}
