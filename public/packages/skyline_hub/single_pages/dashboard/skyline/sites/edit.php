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
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Validation\CSRF\Token;

/** @var $entry Site */
/** @var $form Form */
/** @var $token Token */

?>
<form action="<?=$controller->action('submit')?>" method="post">
    <?php echo $token->output("save_skyline_site_entity"); ?>
    
    <div class="form-group">
        <?php echo $form->label(
            "name",
            t("Name"),
            [
                "class" => "control-label"
            ]
        ); ?>
        
        <?php echo $form->text(
            "name",
            $entry->getName(),
            [
                "class" => "form-control",
                "max-length" => "255",
            ]
        ); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label(
            "handle",
            t("Handle"),
            [
                "class" => "control-label"
            ]
        ); ?>
        
        <?php echo $form->text(
            "handle",
            $entry->getHandle(),
            [
                "class" => "form-control",
                "max-length" => "255",
            ]
        ); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label(
            "subscriptionId",
            t("Subscription ID"),
            [
                "class" => "control-label"
            ]
        ); ?>
        
        <?php echo $form->text(
            "subscriptionId",
            $entry->getSubscriptionId(),
            [
                "class" => "form-control",
                "max-length" => "255",
            ]
        ); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label(
            "subscriptionStatus",
            t("Subscription Status"),
            [
                "class" => "control-label"
            ]
        ); ?>
        
        <?php echo $form->text(
            "subscriptionStatus",
            $entry->getSubscriptionStatus(),
            [
                "class" => "form-control",
                "max-length" => "255",
            ]
        ); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label(
            "neighborhood",
            t("Neighborhood"),
            [
                "class" => "control-label"
            ]
        ); ?>
        
        <?php echo $form->text(
            "neighborhood",
            $entry->getNeighborhood(),
            [
                "class" => "form-control",
                "max-length" => "255",
            ]
        ); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label(
            "adminPassword",
            t("Admin Password"),
            [
                "class" => "control-label"
            ]
        ); ?>
        
        <?php echo $form->password(
            "adminPassword",
            $entry->getAdminPassword(),
            [
                "class" => "form-control",
                "autocomplete" => "off",
                "max-length" => "255",
            ]
        ); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label(
            "status",
            t("Status"),
            [
                "class" => "control-label"
            ]
        ); ?>
        
        <?php echo $form->number(
            "status",
            $entry->getStatus(),
            [
                "class" => "form-control",
                "max-length" => "255",
                "step" => "1",
            ]
        ); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label(
            "suspendedTimestamp",
            t("Suspended Timestamp"),
            [
                "class" => "control-label"
            ]
        ); ?>
        
        <?php echo $form->number(
            "suspendedTimestamp",
            $entry->getSuspendedTimestamp(),
            [
                "class" => "form-control",
                "max-length" => "255",
                "step" => "1",
            ]
        ); ?>
    </div>
    
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
