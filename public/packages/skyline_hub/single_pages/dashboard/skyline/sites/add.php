<?php

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Validation\CSRF\Token;

/** @var $neighborhoodList \PortlandLabs\Skyline\NeighborhoodList */
/** @var $userSelector \Concrete\Core\Form\Service\Widget\UserSelector */
/** @var $form Form */
/** @var $token Token */

?>
<form action="<?=$controller->action('submit')?>" method="post">
    <?php echo $token->output("submit"); ?>

    <fieldset>
        <legend><?=t('Basics')?></legend>
        <div class="mb-3">
            <?php echo $form->label('name', t('Name'))?>
            <?php echo $form->text('name', ['required' => 'required'])?>
        </div>

        <div class="mb-3">
            <?php echo $form->label('author', t('Owned By User'))?>
            <?php // Note - I used the classes field here to intentionally strip out `form-select` as a class because it collides with our ajax select library and makes it look like two selects are present.` ?>

            <?php echo $userSelector->quickSelect('author', false, ['classes' => 'w-100'])?>
            <div class="help-block"><?=t('All sites must belong to a registered user account.')?></div>
        </div>

        <div class="mb-3">
            <?php echo $form->label('neighborhood', t('Neighborhood'))?>
            <?php echo $form->select('neighborhood', ['' => t('** Select Neighborhood')] + $neighborhoodList->asAssociativeArray(), ['required' => 'required'])?>
            <div class="help-block"><?=t('Sites must be provisioned on a neighborhood (shared hosting server).')?></div>
        </div>
    </fieldset>

    <hr>

    <fieldset>
        <legend><?=t('Options')?></legend>
        <div class="mb-3">
            <div class="form-check">
                <?php echo $form->checkbox('provisionAccount', 1, true); ?>
                <?php echo $form->label('provisionAccount', t('Setup hosting account on selected neighborhood'), ['class' => 'form-check-label']); ?>
            </div>

        </div>

    </fieldset>

    <?php if ($_ENV['SKYLINE_ENABLE_TESTING_TOOLS']) { ?>

    <fieldset>
        <legend><?=t('Testing')?></legend>
        <div class="mb-3">
            <div class="form-check">
                <?php echo $form->checkbox('attachToTestClock', 1); ?>
                <?php echo $form->label('attachToTestClock', t('Attach subscription and customer to Test Clock'), ['class' => 'form-check-label']); ?>
                (<a href="https://stripe.com/docs/billing/testing/test-clocks" target="_blank"><?=t('More Information')?></a>)
            </div>

        </div>

        <div class="help-block">Note: testing tools are only available on environments with the environment variable <code>SKYLINE_ENABLE_TESTING_TOOLS</code> set to true.</div>

    </fieldset>
    <?php } ?>



    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <a href="<?php echo Url::to("/dashboard/skyline/sites"); ?>" class="btn btn-secondary">
                <i class="fa fa-chevron-left"></i> <?php echo t('Back'); ?>
            </a>

            <div class="float-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> <?php echo t('Save'); ?>
                </button>
            </div>
        </div>
</form>
