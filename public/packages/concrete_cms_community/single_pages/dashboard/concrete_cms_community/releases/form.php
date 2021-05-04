<?php

defined('C5_EXECUTE') or die("Access Denied.");

$datePicker = new \Concrete\Core\Form\Service\Widget\DateTime();
if ($release) {
    $buttonText = t('Save');
    $action = $view->action('update_release', $release->getId());
    $tokenString = 'update_release';
    $backURL = URL::to('/dashboard/concrete_cms_community/releases', 'view_details', $release->getId());
    $version = $release->getVersion();
    $dateReleased = $release->getDateReleased();
    $downloadUrl = $release->getDownloadUrl();
    $releaseNotes = $release->getReleaseNotes();
    $releaseNotesUrl = $release->getReleaseNotesUrl();
    $upgradeNotes = $release->getUpgradeNotes();
    $isPublished = $release->isPublished();
    $md5sum = $release->getMD5Sum();
} else {
    $version = '';
    $dateReleased = '';
    $downloadUrl = '';
    $releaseNotes = '';
    $releaseNotesUrl = '';
    $upgradeNotes = '';
    $isPublished = true;
    $md5sum = '';
    $buttonText = t('Create Release');
    $action = $view->action('create_release');
    $tokenString = 'create_release';
    $backURL = URL::to('/dashboard/concrete_cms_community/releases');
}
?>


<form method="post" action="<?=$action?>">
    <?=$token->output($tokenString)?>

    <fieldset class="mt-0">
        <div class="form-group">
            <?= $form->label('version', t('Version')) ?>
            <?= $form->text('version', $version) ?>
        </div>
        <div class="form-group">
            <?= $form->label('dateReleased', t('Date Released')) ?>
            <?= $datePicker->date('dateReleased', $dateReleased)?>
        </div>
        <div class="form-group">
            <?= $form->label('downloadUrl', t('Download URL')) ?>
            <?= $form->text('downloadUrl', $downloadUrl) ?>
        </div>
        <div class="form-group">
            <?= $form->label('releaseNotesUrl', t('Release Notes URL')) ?>
            <?= $form->text('releaseNotesUrl', $releaseNotesUrl) ?>
        </div>
        <div class="form-group">
            <?= $form->label('releaseNotes', t('Release Notes')) ?>
            <?= $form->textarea('releaseNotes', $releaseNotes, ['rows' => 15]) ?>
        </div>
        <div class="form-group">
            <?= $form->label('upgradeNotes', t('Upgrade Notes')) ?>
            <?= $form->textarea('upgradeNotes', $upgradeNotes, ['rows' => 5]) ?>
            <div class="help-block"><?=t('This can usually be left blank. Only put something in here if it must be run manually prior to upgrade.')?></div>
        </div>
        <div class="form-group">
            <label class="control-label"><?=t('Published')?></label>
            <div class="form-check">
                <?= $form->radio('isPublished', 1, $isPublished) ?>
                <label class="form-check-label" for="isPublished1">
                    <?=t("Yes, this update is published and ready to download.")?>
                </label>
            </div>
            <div class="form-check">
                <?= $form->radio('isPublished', 0, $isPublished) ?>
                <label class="form-check-label" for="isPublished2">
                    <?=t("No, do not publish this update yet.")?>
                </label>
            </div>
        </div>
        <div class="form-group">
            <?= $form->label('md5sum', t('MD5 Checksum')) ?>
            <?= $form->text('md5sum', $md5sum) ?>
        </div>
    </fieldset>


    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions ">
            <a href="<?=$backURL?>"
               class="btn btn-secondary"><?=t('Cancel')?></a>
            <button type="submit" class="btn btn-primary float-right"><?=$buttonText?></button>
        </div>
    </div>

</form>

