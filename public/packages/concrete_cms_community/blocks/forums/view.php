<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Url;

defined('C5_EXECUTE') or die('Access denied');

/** @var array $topics */
?>

<div class="public-profile" style="margin-top: 0">
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <?php echo t("Forums"); ?>

            <?php if ($isOwnProfile) { ?>
                <a href="<?php echo (string)Url::to("/api/v1/discourse/edit_forum_info"); ?>" target="_blank"
                   class="ml-auto btn btn-sm btn-secondary float-end">
                    <?php echo t("Edit Forum Info"); ?>
                </a>
            <?php } ?>
        </div>

        <div class="card-body">

        <div class="card-text">
            <?php if (count($topics) > 0) { ?>
                <ul class="post-list">
                    <?php foreach ($topics as $topic) { ?>
                        <li>
                            <a href="<?php echo h($topic["url"]); ?>" target="_blank">
                                <?php echo $topic["title"]; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p class="none-entered no-posts">
                    <?php echo t("This user has no posts yet."); ?>
                </p>
            <?php } ?>

            <?php if ($isOwnProfile) { ?>
                <?php echo t("Visit your profile in forums %s.", sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", (string)Url::to("/api/v1/discourse/edit_forum_info"), t("here"))) ?>
            <?php } ?>
        </div>

        </div>
    </div>
</div>