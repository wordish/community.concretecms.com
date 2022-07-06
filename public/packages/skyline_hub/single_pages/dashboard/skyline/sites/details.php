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

/** @var $hostingSite Site */

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
            <form method="post" action="<?=$view->action('delete', $hostingSite->getID())?>">
                <?=Core::make("token")->output('delete')?>
                <input type="hidden" name="id" value="<?=$hostingSite->getID()?>">
                <p><?=t('Are you sure you want to delete this hosting site record? <b>This will remove the hosting site from its server</b>.')?></p>
                <div class="dialog-buttons">
                    <button class="btn btn-secondary" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
                    <button class="btn btn-danger ms-auto" onclick="$('#ccm-dialog-delete-skyline-site form').submit()"><?=t('Delete Site')?></button>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<h2><?=$hostingSite->getName()?></h2>
<div class="text-secondary"><?=$hostingSite->getHandle()?>@<?=$hostingSite->getNeighborhood()?></div>

<hr>

<?php if ($hostingSite->getStatus() == Site::STATUS_INSTALLING) { ?>
    <div class="card">
        <div class="card-body" vue-skyline>
            <skyline-installation-progress :site='<?=json_encode($hostingSite)?>'></skyline-installation-progress>
        </div>
    </div>
<?php } ?>