<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Localization\Service\Date;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use PortlandLabs\CommunityBadges\User\Point\Entry;
use Concrete\Core\User\User;
use Concrete\Core\Config\Repository\Repository;

/** @var Entry[] $entries */
/** @var array $myTotalList */
/** @var bool $hasNextPage */

$app = Application::getFacadeApplication();
/** @var Date $dateService */
$dateService = $app->make(Date::class);
/** @var Repository $config */
$config = $app->make(Repository::class);
?>

<div class="karma-page">
    <div class="container">
        <?php
        $a = new \Concrete\Core\Area\Area('Main');
        $a->enableGridContainer();
        $a->display($c);
        ?>

        <div class="row mt-2">
            <div class="col-lg-8" id="karma-list">
                <div class="card">
                    <div class="card-header d-flex">
                        <?=$karmaDescriptionText?>
                        <div class="dropdown">
                            <a data-bs-toggle="dropdown" data-bs-auto-close="outside" data-toggle="dropdown" class="text-gray font-size-lg"><i class="fa fa-filter"></i></a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <form class="px-2">
                                    <label class="control-label"><?=t("Awarded To")?></label>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="filterUser" id="filter1" value="user" <?php if ($filterUser == 'user') { ?>checked<?php } ?>>
                                            <label class="form-check-label" for="filter1">
                                                <?=t('Just %s', $profile->getUserDisplayName())?>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="filterUser" id="filter2" value="all" <?php if ($filterUser == 'all') { ?>checked<?php } ?>>
                                            <label class="form-check-label" for="filter2">
                                                <?=t('Everyone')?>
                                            </label>
                                        </div>

                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <label class="control-label"><?=t("Sort By")?></label>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sortList" id="sortList1" value="recent" <?php if ($sortList == 'recent') { ?>checked<?php } ?>>
                                            <label class="form-check-label" for="sortList1">
                                                <?=t('Most Recent')?>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sortList" id="sortList2" value="points" <?php if ($sortList == 'points') { ?>checked<?php } ?>>
                                            <label class="form-check-label" for="sortList2">
                                                <?=t('Points Won')?>
                                            </label>
                                        </div>

                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary"><?=t('Search')?></button>
                                    </div>
                                </form>
                            </div>

                        </div>

                    </div>
                    <div class="card-body">

                        <div class="card-text">
                            <?php if (count($entries) === 0) { ?>
                                <p>
                                    <?php echo t("No karma entries available."); ?>
                                </p>
                            <?php } else { ?>
                                <div id="karma-container">
                                    <?php foreach ($entries as $entry) { ?>
                                        <?php
                                        $targetUser = $entry->getUserPointEntryUserObject();
                                        ?>
                                        <div class="karma-entry">
                                            <div class="row">
                                                <div class="col-auto profile-picture">
                                                    <div class="profile-image">
                                                        <div class="image-wrapper">
                                                            <?php echo $targetUser->getUserAvatar()->output(); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col infos">
                                                    <p class="text-muted">
                                                        <?php
                                                        try {
                                                            $date = $dateService->formatDateTime($entry->getUserPointEntryDateTime());
                                                        } catch (Exception $e) {
                                                            $date = t("n/a");
                                                        }

                                                        /** @noinspection HtmlUnknownTarget */
                                                        echo t("Awarded to %1$s on %2$s",
                                                            sprintf(
                                                                "<a href=\"%s\">%s</a>",
                                                                (string)Url::to("/members/profile", $entry->getUserPointEntryUserID()),
                                                                $targetUser->getUserName()
                                                            ),
                                                            $date
                                                        );
                                                        ?>
                                                    </p>

                                                    <h3>
                                                        <?php
                                                        if (is_object($entry->getUserPointEntryActionObject())) {
                                                            echo $entry->getUserPointEntryActionObject()->getUserPointActionName();
                                                        } else {
                                                            echo t("Received Extra-Karma");
                                                        }
                                                        ?>
                                                    </h3>

                                                    <p>
                                                        <?php
                                                        if (strlen($entry->getUserPointEntryDescription()) > 0) {
                                                            echo $entry->getUserPointEntryDescription();
                                                        } else {
                                                            echo t("Thanks for taking the time!");
                                                        }
                                                        ?>
                                                    </p>
                                                </div>


                                                <div class="col col-2 points">
                                                    <h3 class="float-end">
                                                        <?php echo number_format($entry->getUserPointEntryValue()); ?>
                                                    </h3>
                                                </div>
                                            </div>

                                            <div class="clearfix"></div>
                                        </div>

                                    <?php } ?>
                                </div>

                            <?php } ?>
                        </div>
                    </div>
                    <div id="load-more" data-load-more-url="<?=$loadMoreURL?>" class="card-footer bg-white <?php echo $hasNextPage ? "" : "d-none"; ?>">
                        <div class="text-center text-gray">
                            <a href="javascript:void(0)"><?=t('Load More')?></a>
                            <i class="fa fa-spin fa-sync-alt d-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4" id="my-karma">
                <div class="card">
                    <div class="card-header">
                        <?php echo t("My Karma"); ?>
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            <div class="karma-totals">
                                <?php $totalSum = 0; ?>

                                <?php foreach ($myTotalList as $actionName => $totalPoints) { ?>
                                    <div class="row">
                                        <div class="karma-total-action col-7 text-right">
                                            <?php echo $actionName; ?>
                                        </div>

                                        <div class="karma-total-amount pl-0 col-5">
                                            <?php echo number_format($totalPoints); ?>
                                        </div>

                                        <?php $totalSum += $totalPoints; ?>
                                    </div>
                                <?php } ?>

                                <?php if ($totalSum === 0) { ?>
                                    <p>
                                        <?php echo t("You have not earned any karma points yet."); ?>
                                    </p>
                                <?php } else { ?>
                                    <div class="row d-flex mt-5">
                                    <div class="karma-total-action col-7 text-right mt-auto">
                                        <b><?php echo t("Total:"); ?></b>
                                    </div>

                                    <div class="col-5 pl-0 karma-total-all-amount mt-auto">
                                        <?php echo number_format($totalSum); ?>
                                    </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <hr class="mt-4 mb-4">

                            <div class="karma-request">
                                <strong>
                                    <?php echo t("Karma Request"); ?>
                                </strong>

                                <p>
                                    <?php echo t("Have you done something that you think you should get karma for? Tell us!"); ?>
                                </p>

                                <div class="text-center mt-4 mb-4">
                                    <a href="<?php echo (string)Url::to(Page::getByID($config->get("concrete_cms_community.submit_karma_request_page", false))); ?>"
                                       class="btn btn-primary">
                                        <?php echo t("Submit Karma Request"); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
