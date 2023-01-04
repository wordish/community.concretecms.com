<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Entity\File\File;
use Concrete\Core\Entity\File\Version;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\User\UserInfo;
use Concrete\Package\ConcreteCmsCommunity\Controller;

/** @var UserInfo $profile */
/** @var bool $isOwnProfile */
/** @var Entry[] $showcaseItems */

$app = Application::getFacadeApplication();
/** @var PackageService $packageService */
$packageService = $app->make(PackageService::class);

/** @var Controller $pkg */
$pkg = $packageService->getByHandle("concrete_cms_community")->getController();
?>

<div class="public-profile" style="margin-top: 0">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <?php echo t("Showcase"); ?>

            <?php if ($isOwnProfile) { ?>
                <a href="javascript:void(0);"
                   class="ms-auto btn btn-sm btn-secondary float-end create-showcase-item">
                    <?php echo t("Add Showcase Item"); ?>
                </a>
            <?php } ?>
        </div>

        <div class="card-body">

            <div class="card-text">
            <?php if (count($showcaseItems) === 0) { ?>
                <?php echo t("No showcase items available yet."); ?>
            <?php } else { ?>
                <div class="row">
                    <?php foreach ($showcaseItems as $showcaseItem) { ?>
                        <div class="col-sm-6 col-md-4">
                            <?php
                            $showcaseItemImageUrl = $pkg->getRelativePath() . "/images/default_showcase_item.png";

                            $requiredImage = $showcaseItem->getAttribute("required_image");
                            if ($requiredImage instanceof File) {
                                $approvedVersion = $requiredImage->getApprovedVersion();
                                if ($approvedVersion instanceof Version) {
                                    $showcaseItemImageUrl = $approvedVersion->getURL();
                                }
                            }
                            ?>

                            <div class="showcase-item">
                                <?php if ($isOwnProfile) { ?>
                                    <a href="javascript:void(0);"
                                       data-showcase-item-id="<?php echo h($showcaseItem->getID()); ?>"
                                       class="edit-showcase-item">
                                        <img src="<?php echo h($showcaseItemImageUrl); ?>"
                                             alt="<?php echo h($showcaseItem->getAttribute("title")); ?>"
                                             class="img-fluid">
                                    </a>

                                    <a href="javascript:void(0);"
                                       data-showcase-item-id="<?php echo h($showcaseItem->getID()); ?>"
                                       class="edit-showcase-item">
                                        <h2>
                                            <?php echo $showcaseItem->getAttribute("title"); ?>
                                        </h2>
                                    </a>

                                    <p>
                                        <?php echo $showcaseItem->getAttribute("short_description"); ?>
                                    </p>

                                    <a href="javascript:void(0);"
                                       data-showcase-item-id="<?php echo h($showcaseItem->getID()); ?>"
                                       class="remove-showcase-item btn btn-sm btn-danger">
                                        <?php echo t("Remove"); ?>
                                    </a>

                                    <a href="javascript:void(0);"
                                       data-showcase-item-id="<?php echo h($showcaseItem->getID()); ?>"
                                       class="edit-showcase-item btn btn-sm btn-secondary">
                                        <?php echo t("Edit"); ?>
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo h($showcaseItem->getAttribute("site_url")); ?>">
                                        <img src="<?php echo h($showcaseItemImageUrl); ?>"
                                             alt="<?php echo h($showcaseItem->getAttribute("title")); ?>"
                                             class="img-fluid">
                                    </a>

                                    <a href="<?php echo h($showcaseItem->getAttribute("site_url")); ?>">
                                        <h2>
                                            <?php echo $showcaseItem->getAttribute("title"); ?>
                                        </h2>
                                    </a>

                                    <p>
                                        <?php echo $showcaseItem->getAttribute("short_description"); ?>
                                    </p>

                                    <a href="<?php echo h($showcaseItem->getAttribute("site_url")); ?>"
                                       class="btn btn-sm btn-secondary">
                                        <?php echo t("Visit Site"); ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        </div>
    </div>
</div>