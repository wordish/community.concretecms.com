<?php defined('C5_EXECUTE') or die("Access Denied.");

$dh = Core::make('helper/date'); /* @var $dh \Concrete\Core\Localization\Service\Date */

$soId = (int) $profile->getAttribute('stackoverflow_user_id');
if ($soId) {
    $soReputation = $profile->getAttribute('stackoverflow_reputation') ?? 0;
    $soBronze = $profile->getAttribute('stackoverflow_badges_bronze') ?? 0;
    $soSilver = $profile->getAttribute('stackoverflow_badges_silver') ?? 0;
    $soGold = $profile->getAttribute('stackoverflow_badges_gold') ?? 0;
}
?>

    <div class="row">
        <div class="col-sm-1">
            <?php echo $profile->getUserAvatar()->output(); ?>
        </div>
        <div class="col-sm-11">

            <h1><?= h($profile->getUserName()) ?></h1>

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

    <?php
    if ($soId) {
        ?>
        <div class="row">
            <div class="col-sm-4">
                <i class="fa fa-stack-overflow"></i>
                <a href="https://stackoverflow.com/users/<?= $soId ?>"><?= t('Stackoverflow Profile') ?></a>
            </div>
            <div class="col-sm-4">
                <i class="fa fa-stack-overflow"></i>
                <?= t('%s Reputation', number_format($soReputation)) ?>
            </div>
            <div class="col-sm-4">
                <i class="fa fa-stack-overflow"></i>
                <?= t('Badges') ?>
                <span style="color:#cda400">● <?= number_format((int) $soGold) ?></span>
                <span style="color:#8c9298">● <?= number_format((int) $soSilver) ?></span>
                <span style="color:#c38b5f">● <?= number_format((int) $soBronze) ?></span>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>
    </div>

