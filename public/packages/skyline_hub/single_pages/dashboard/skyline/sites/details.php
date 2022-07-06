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

<div>
    <h2><?=$hostingSite->getName()?></h2>
    <div>
        <span class="text-secondary"><?=$hostingSite->getHandle()?>@<?=$hostingSite->getNeighborhood()?></span>
        <?=$hostingSite->getStatusBadge()?>
    </div>
</div>
<hr>

<fieldset class="mb-5">
    <legend><?=t('Skyline Account')?></legend>
    <div class="row mb-3">
        <div class="col-md-3 text-end"><b><?=t('Status')?></b></div>
        <div class="col-md-9"><?=$hostingSite->getStatusText()?></div>
    </div>
    <div class="row mb-3 d-flex align-items-center">
        <div class="col-md-3 text-end"><b><?=t('Public URL')?></b></div>
        <div class="col-md-9">
            <div class="input-group">
                <input type="text" class="form-control bg-white" readonly value="<?=$hostingSite->getPublicUrl()?>" onclick="this.select()">
                <a target="_blank" href="<?=$hostingSite->getPublicUrl()?>" class="px-2 btn btn-secondary"><i class="fa fa-external-link-alt"></i></a>
            </div>
        </div>
    </div>
    <div class="row mb-3 d-flex align-items-center">
        <div class="col-md-3 text-end"><b><?=t('Control Panel URL')?></b></div>
        <div class="col-md-9">
            <div class="input-group">
                <input type="text" class="form-control bg-white" readonly value="<?=$hostingSite->getControlPanelUrl()?>" onclick="this.select()">
                <a target="_blank" href="<?=$hostingSite->getControlPanelUrl()?>" class="px-2 btn btn-secondary"><i class="fa fa-external-link-alt"></i></a>
            </div>
        </div>
    </div>
</fieldset>

<hr>

<fieldset class="mt-5">
<legend><?=t('Stripe')?></legend>
    <div class="row mb-3">
        <div class="col-md-3 text-end"><b><?=t('Subscription Status')?></b></div>
        <div class="col-md-9"><?=$hostingSite->getSubscriptionStatus()?> (<a href="https://stripe.com/docs/api/subscriptions/object#subscription_object-status" target="_blank">Stripe Docs <i class="fa fa-external-link-alt"></i></a>) </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 text-end"><b><?=t('Subscription ID')?></b></div>
        <div class="col-md-9"><a target="_blank"href="https://dashboard.stripe.com/subscriptions/<?=$hostingSite->getSubscriptionId()?>"><?=$hostingSite->getSubscriptionId()?></div>
    </div>

</fieldset>



<?php if ($hostingSite->getStatus() == Site::STATUS_INSTALLING) { ?>
    <div class="card">
        <div class="card-body" vue-skyline>
            <skyline-installation-progress :site='<?=json_encode($hostingSite)?>'></skyline-installation-progress>
        </div>
    </div>
<?php } ?>