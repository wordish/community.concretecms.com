<?php

defined('C5_EXECUTE') or die('Access denied');


/** @var $form \Concrete\Core\Form\Service\Form */
/** @var $token \Concrete\Core\Validation\CSRF\Token */
/** @var $hostingSite \PortlandLabs\Skyline\Entity\Site */
/** @var $userSelector \Concrete\Core\Form\Service\Widget\UserSelector */

$authorID = null;
if ($hostingSite->getAuthor()) {
    $authorID = $hostingSite->getAuthor()->getUserID();
}
?>
<form action="<?=$controller->action('submit', $hostingSite->getId())?>" method="post">
    <?php echo $token->output("submit"); ?>
    <fieldset>
        <legend><?=t('Basics')?></legend>
        <div class="mb-3">
            <?php echo $form->label('name', t('Name'))?>
            <?php echo $form->text('name', $hostingSite->getName(), ['required' => 'required'])?>
        </div>
        <div class="mb-3">
            <?php echo $form->label('author', t('Owned By User'))?>
            <?php echo $userSelector->quickSelect('author', $authorID, ['classes' => 'w-100'])?>
        </div>
    </fieldset>

    <fieldset>
        <legend><?=t('Stripe')?></legend>
        <div class="help-block"><?=t("Only edit these fields if you know what you're doing.")?></div>
        <div class="mb-3">
            <?php echo $form->label('subscriptionId', t('Subscription ID'))?>
            <?php echo $form->text('subscriptionId', $hostingSite->getSubscriptionId())?>
        </div>
    </fieldset>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <a href="<?=$backURL?>" class="btn btn-secondary">
                <i class="fa fa-chevron-left"></i> <?php echo t('Back'); ?>
            </a>

            <div class="float-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> <?php echo t('Save'); ?>
                </button>
            </div>
        </div>
</form>
