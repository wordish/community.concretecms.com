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

$numberHelper = new \Concrete\Core\Utility\Service\Number();


?>

<div class="ccm-dashboard-header-buttons">
    <div class="btn-group">
        <a href="<?=$backURL?>" class="btn btn-secondary"><?=t("Back")?></a>
        <?php if (isset($editURL) && $editURL) { ?>
            <a href="<?=$editURL?>" class="btn btn-secondary"><?=t("Edit")?></a>
        <?php } ?>
        <?php if ($allowSuspend) { ?>
            <button type="button" class="btn btn-secondary text-danger" data-dialog="suspend-site"><?= t('Suspend') ?></button>
        <?php } ?>
        <?php if ($allowReinstate) { ?>
            <button type="button" class="btn btn-secondary text-success" data-dialog="reinstate-site"><?= t('Reinstate') ?></button>
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

<?php if ($allowDelete) { ?>
    <div style="display: none">
        <div id="ccm-dialog-reinstate-skyline-site" data-dialog-wrapper="reinstate-site" class="ccm-ui">
            <form method="post" action="<?=$view->action('reinstate', $hostingSite->getID())?>">
                <?=Core::make("token")->output('reinstate')?>
                <input type="hidden" name="id" value="<?=$hostingSite->getID()?>">
                <p><?=t('Are you sure you want to reinstate this hosting site record? The site will be brought back online.')?></p>
                <div class="dialog-buttons">
                    <button class="btn btn-secondary" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
                    <button class="btn btn-success ms-auto" onclick="$('#ccm-dialog-reinstate-skyline-site form').submit()"><?=t('Reinstate Site')?></button>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<?php if ($allowSuspend) { ?>
    <div style="display: none">
        <div id="ccm-dialog-suspend-skyline-site" data-dialog-wrapper="suspend-site" class="ccm-ui">
            <form method="post" action="<?=$view->action('suspend', $hostingSite->getID())?>">
                <?=Core::make("token")->output('suspend')?>
                <input type="hidden" name="id" value="<?=$hostingSite->getID()?>">
                <p><?=t('Are you sure you want to suspend this hosting site record? The site will be inaccessible, but the data and files will remain. The site may be reinstated at a future point, but suspended sites are automatically removed after %s days', $_ENV['SKYLINE_DAYS_AFTER_SUSPENDING_TO_KEEP_SITE'])?></p>
                <div class="dialog-buttons">
                    <button class="btn btn-secondary" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
                    <button class="btn btn-danger ms-auto" onclick="$('#ccm-dialog-suspend-skyline-site form').submit()"><?=t('Suspend Site')?></button>
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
        <div class="col-md-3 text-end"><b><?=t('Account Handle')?></b></div>
        <div class="col-md-9">
            <input type="text" class="form-control bg-white" readonly value="<?=$hostingSite->getHandle()?>" onclick="this.select()">
        </div>
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

<fieldset class="mt-5 mb-5">
    <legend><?=t('Stripe')?></legend>
    <div class="row mb-3">
        <div class="col-md-3 text-end"><b><?=t('Subscription Status')?></b></div>
        <div class="col-md-9"><?=$hostingSite->getSubscriptionStatus()?> (<a href="https://stripe.com/docs/api/subscriptions/object#subscription_object-status" target="_blank">Stripe Docs <i class="fa fa-external-link-alt"></i></a>) </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3 text-end"><b><?=t('Subscription ID')?></b></div>
        <div class="col-md-9"><a target="_blank" href="https://dashboard.stripe.com/subscriptions/<?=$hostingSite->getSubscriptionId()?>"><?=$hostingSite->getSubscriptionId()?></a></div>
    </div>

</fieldset>

<hr>

<fieldset class="mt-5 mb-5">
    <div class="d-flex">
        <legend><?=t('Backups')?></legend>
        <div class="ml-auto">
            <form method="post" action="<?=$view->action('create_backup', $hostingSite->getID())?>">
                <?=$token->output('create_backup')?>
                <button type="submit" class="btn btn-secondary btn-sm text-nowrap"><?=t('Create Backup')?>
            </form>
        </div>
    </div>


    <?php
    $backups = $hostingSite->getBackups();
    if (count($backups)) { ?>

        <table class="table">
            <thead>
            <tr>
                <th class="w-50"><?=t('Date Created')?></th>
                <th class="w-50"><?=t('Size')?></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($backups as $backup) { ?>

                <tr>
                    <td class="w-50"><?php
                        echo (new DateTime)->setTimestamp($backup->getDateCreated())->format('F d Y \a\t g:i a');
                        ?></td>
                    <?php if ($backup->isLoading()) { ?>
                        <td><span class="text-muted"><?=t('Pending...')?></span></td>
                        <td colspan="2" class="align-middle"><div class="d-flex"><i class="ms-auto fa fa-spinner fa-spin"></i></div></td>
                    <?php } else { ?>
                        <td class="w-25"><?=$numberHelper->formatSize($backup->getSize())?></td>
                        <td><a href="<?=$view->action('download_backup', $backup->getID())?>" class="ccm-hover-icon"><i class="fa fa-download"></i></a></td>
                        <td><a href="javascript:void(0)" data-dialog-title="<?=t('Delete Backup')?>" data-dialog="delete-backup-<?=$backup->getID()?>" class="ccm-hover-icon"><i class="fa fa-trash"></i></a></td>
                    <?php } ?>
                </tr>

            <?php } ?>
            </tbody>
        </table>

        <?php foreach ($backups as $backup) { ?>
            <div style="display: none">
                <div data-dialog-wrapper="delete-backup-<?=$backup->getID()?>" class="ccm-ui">
                    <form method="post" action="<?=$view->action('delete_backup', $backup->getID())?>">
                        <?=Core::make("token")->output('delete_backup')?>
                        <input type="hidden" name="id" value="<?=$hostingSite->getID()?>">
                        <p><?=t('Are you sure you want to delete this hosting site backup?')?></p>
                        <div class="dialog-buttons">
                            <button class="btn btn-secondary" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
                            <button class="btn btn-danger ms-auto" onclick="$('div[data-dialog-wrapper=delete-backup-<?=$backup->getID()?>] form').submit()"><?=t('Delete Backup')?></button>
                        </div>
                    </form>
                </div>
            </div>

        <?php } ?>
    <?php } else { ?>

    <p><?=t('No backups have been created.')?></p>

    <?php }
    ?>

</fieldset>

<hr>


    <fieldset class="mt-5">
        <legend><?=t('Storage Space &amp; Quota')?></legend>

        <?php
        View::element('account/quota', ['hostingSite' => $hostingSite], 'skyline_hub');
        ?>

    </fieldset>



<?php if ($hostingSite->getStatus() == Site::STATUS_INSTALLING) { ?>
    <hr>
    <fieldset class="mt-5">
    <legend><?=t('Installation Progress')?></legend>
    <div class="card">
        <div class="card-body" vue-skyline>
            <skyline-installation-progress :site='<?=h(json_encode($hostingSite))?>'></skyline-installation-progress>
        </div>
    </div>
    </fieldset>
<?php } ?>