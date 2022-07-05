<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */

defined('C5_EXECUTE') or die('Access denied');

use PortlandLabs\Skyline\Entity\Site;

/** @var $entry Site */

?>

<div class="ccm-dashboard-header-buttons">
    <div class="btn-group">
        <a href="<?=$backURL?>" class="btn btn-secondary"><?=t("Back")?></a>
        <?php if (isset($editURL) && $editURL) { ?>
            <a href="<?=$editURL?>" class="btn btn-secondary"><?=t("Edit")?></a>
        <?php } ?>
        <?php if ($allowDelete) { ?>
            <button type="button" class="btn btn-danger" data-dialog="delete-site"><?= t('Delete') ?></button>
        <?php } ?>
    </div>
</div>

<?php if ($allowDelete) { ?>
    <div style="display: none">
        <div id="ccm-dialog-delete-skyline-site" data-dialog-wrapper="delete-site" class="ccm-ui">
            <form method="post" action="<?=$view->action('remove', $entry->getID())?>">
                <?=Core::make("token")->output('remove')?>
                <input type="hidden" name="entry_id" value="<?=$entry->getID()?>">
                <p><?=t('Are you sure you want to delete this hosting site record? This cannot be undone.')?></p>
                <div class="dialog-buttons">
                    <button class="btn btn-secondary" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
                    <button class="btn btn-danger ms-auto" onclick="$('#ccm-dialog-delete-skyline-site form').submit()"><?=t('Delete Site')?></button>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<h2><?=$entry->getName()?></h2>
<div class="text-secondary"><?=$entry->getHandle()?>@<?=$entry->getNeighborhood()?></div>

<hr>
