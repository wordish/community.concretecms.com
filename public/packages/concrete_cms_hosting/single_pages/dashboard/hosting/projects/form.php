<?php

defined('C5_EXECUTE') or die("Access Denied.");

/** @var \Concrete\Core\Form\Service\Widget\UserSelector $userSelector */
$userSelector = app(Concrete\Core\Form\Service\Widget\UserSelector::class);

if ($project) {
    $buttonText = t('Save');
    $action = $view->action('update_project', $project->getId());
    $tokenString = 'update_project';
    $backURL = URL::to('/dashboard/hosting/projects', 'view_details', $project->getId());
    $name = $project->getName();
    $userId = $project->get;
    if ($project instanceof \PortlandLabs\Hosting\Project\Project && !$project instanceof \PortlandLabs\Hosting\Project\LagoonProject) {
        $projectType = 'project';
    } else {
        $projectType = 'lagoon';
        $lagoonId = $project->getLagoonId();
    }
} else {
    $buttonText = t('Create Project');
    $action = $view->action('create_project');
    $tokenString = 'create_project';
    $backURL = URL::to('/dashboard/hosting/projects');
}
?>


<form method="post" action="<?=$action?>">
    <?=$token->output($tokenString)?>
    <?=$form->hidden('projectType', $projectType);?>

    <fieldset class="mt-0">
        <div class="form-group">
            <?= $form->label('name', t('Name')) ?>
            <?= $form->text('name', $name) ?>
        </div>

        <div class="form-group">
            <?= $form->label('userId', t('Owner')) ?>
            <?=/** @TODO Replace with multiselect when that works */$userSelector->quickSelect('userId', $admins[0])?>
        </div>
    </fieldset>

    <?php if ($projectType == 'lagoon') { ?>
        <fieldset>
            <legend><?=t('Concrete Hosting Options')?></legend>
            <div class="form-group">
                <?= $form->label('gitUrl', t('Git URL')) ?>
                <?= $form->text('gitUrl', $gitUrl) ?>
            </div>
            <div class="form-group">
                <?= $form->label('productionBranch', t('Production branch name')) ?>
                <?= $form->text('productionBranch', $productionBranch, ['placeholder' => 'main']) ?>
            </div>
            <div class="form-group">
                <?= $form->label('stageBranches', t('Stage branch names')) ?>
                <?= $form->text('stageBranches', $stageBranches, ['placeholder' => 'stage,dev']) ?>
                <p class="help-block">Comma separated</p>
            </div>
        </fieldset>
    <?php } ?>


    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions ">
            <a href="<?=$backURL?>"
               class="btn btn-secondary"><?=t('Cancel')?></a>
            <button type="submit" class="btn btn-primary float-right"><?=$buttonText?></button>
        </div>
    </div>

</form>

