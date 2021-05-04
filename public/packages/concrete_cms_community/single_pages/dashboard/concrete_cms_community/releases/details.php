<?php

defined('C5_EXECUTE') or die('Access denied');

?>

<div class="ccm-dashboard-dialog-wrapper">
    <div data-dialog-wrapper="delete-release">
        <form method="post" action="<?= $controller->action('delete_release') ?>">
            <?php $token->output('delete_release') ?>
            <input type="hidden" name="id" value="<?= $release->getId() ?>" />
            <p><?=t('Are you sure you want to delete this release? This cannot be undone.') ?></p>
            <div class="dialog-buttons">
                <button class="btn btn-secondary" onclick="jQuery.fn.dialog.closeTop()"><?= t('Cancel') ?></button>
                <button class="btn btn-danger" onclick="$('div[data-dialog-wrapper=delete-release] form').submit()"><?= t('Delete Release') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="ccm-dashboard-header-buttons">
    <div class="btn-group">
        <a href="<?=$view->url('/dashboard/concrete_cms_community/releases', 'edit', $release->getId())?>" class="btn btn-sm btn-secondary"><?=t('Edit')?></a>
        <button class="btn btn-sm btn-danger" data-dialog="delete-release" data-dialog-title="<?= t('Delete Release') ?>" data-dialog-width="400"><?= t('Delete Release') ?></button>
    </div>
</div>

<div class="form-group">
    <?= $form->label('', t('ID')) ?>
    <div><?=$release->getId()?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Version')) ?>
    <div><?=$release->getVersion()?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Date Released')) ?>
    <div><?=$release->getDateReleasedString()?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Download URL')) ?>
    <div><?=$release->getDownloadURL()?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Release Notes')) ?>
    <div><code><pre><?=$release->getReleaseNotes()?></pre></code></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Release Notes URL')) ?>
    <div><?=$release->getReleaseNotesURL()?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Upgrade Notes')) ?>
    <div><?=$release->getUpgradeNotesFormatted()?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Is Published')) ?>
    <div><?=$release->isPublished() ? t('Yes') : t('No')?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('MD5 Checksum')) ?>
    <div><?=$release->getMd5sum()?></div>
</div>
