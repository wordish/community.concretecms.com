<?php

defined('C5_EXECUTE') or die('Access denied');

?>

<div class="ccm-dashboard-dialog-wrapper">
    <div data-dialog-wrapper="delete-project">
        <form method="post" action="<?= $controller->action('delete_project') ?>">
            <?php $token->output('delete_project') ?>
            <input type="hidden" name="id" value="<?= $project->getId() ?>" />
            <p><?=t('Are you sure you want to delete this project? This cannot be undone.') ?></p>
            <div class="dialog-buttons">
                <button class="btn btn-secondary" onclick="jQuery.fn.dialog.closeTop()"><?= t('Cancel') ?></button>
                <button class="btn btn-danger" onclick="$('div[data-dialog-wrapper=delete-project] form').submit()"><?= t('Delete Project') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="ccm-dashboard-header-buttons">
    <div class="btn-group">
        <a href="<?=$view->url('/dashboard/hosting/projects', 'edit', $project->getId())?>" class="btn btn-sm btn-secondary"><?=t('Edit')?></a>
        <button class="btn btn-sm btn-danger" data-dialog="delete-project" data-dialog-title="<?= t('Delete Project') ?>" data-dialog-width="400"><?= t('Delete Project') ?></button>
    </div>
</div>

<div class="form-group">
    <?= $form->label('', t('ID')) ?>
    <div><?=$project->getId()?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Name')) ?>
    <div><?=$project->getName()?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Type')) ?>
    <div><?php
        if ($project instanceof \PortlandLabs\Hosting\Project\LagoonProject) {
            print t('Concrete Hosting');
        } else {
            print t('Third Party');
        }
        ?>
    </div>
</div>

<?php if ($project instanceof \PortlandLabs\Hosting\Project\LagoonProject) { ?>
    <div class="form-group">
        <?= $form->label('', t('Lagoon ID')) ?>
        <div><?=$project->getLagoonId()?></div>
    </div>
<?php } ?>


<div class="form-group">
    <?= $form->label('', t('Date Created')) ?>
    <div><?=$project->getDateCreatedString()?></div>
</div>

<div class="form-group">
    <?= $form->label('', t('Owned By')) ?>
    <div><?=$project->getUserDisplayName()?></div>
</div>
