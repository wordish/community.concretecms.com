<?php defined('C5_EXECUTE') or die("Access Denied.");

$dh = Core::make('helper/date'); /* @var $dh \Concrete\Core\Localization\Service\Date */
?>

    <div class="row">
        <div class="col-sm-1">
            <?php echo $profile->getUserAvatar()->output(); ?>
        </div>
        <div class="col-sm-11">

            <h1><?=$profile->getUserName()?></h1>
        
            <div id="ccm-profile-controls">
                <?php if ($canEdit) {
                    ?>
                    <div class="btn-group">
                        <a href="<?=$view->url('/account/edit_profile')?>" class="btn btn-sm btn-default"><i class="fa fa-cog"></i> <?=t('Edit')?></a>
                        <a href="<?=$view->url('/')?>" class="btn btn-sm btn-default"><i class="fa fa-home"></i> <?=t('Home')?></a>
                    </div>
                    <?php
                } else {
                    ?>
                    <?php if ($profile->getAttribute('profile_private_messages_enabled')) {
                        ?>
                        <a href="<?=$view->url('/account/messages', 'write', $profile->getUserID())?>" class="btn btn-sm btn-default"><i class="fa-user fa"></i> <?=t('Connect')?></a>
                        <?php
                    }
                    ?>
                    <?php
                } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <i class="fa fa-clock-o"></i> <?=t(/*i18n: %s is a date */'Joined on %s', $dh->formatDate($profile->getUserDateAdded(), true))?>
        </div>
        <div class="col-sm-4">
            <i class="fa fa-fire"></i> <?=number_format(\Concrete\Core\User\Point\Entry::getTotal($profile))?> <?=t('Community Points')?>
        </div>
        <div class="col-sm-4">
            <i class="fa fa-bookmark"></i> <a href="#badges"><?=number_format(count($badges))?> <?=t2('Badge', 'Badges', count($badges))?></a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>
    </div>

