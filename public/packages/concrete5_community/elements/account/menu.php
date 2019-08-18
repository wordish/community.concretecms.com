<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="account-menu">
    <div class="list-group">
        <div class="list-group-item">
            <a href="<?=URL::to('/account/welcome')?>"><?=t('Home')?></a>
        </div>
        <div class="list-group-item">
            <div class="account-menu-header">Private Messages</div>
            <ul class="list-unstyled">
                <li><a href="<?=URL::to('/account/messages', 'view_mailbox', 'inbox')?>"><?=t('Inbox')?></a></li>
                <li><a href="<?=URL::to('/account/messages', 'view_mailbox', 'sent')?>"><?=t('Sent Messages')?></a></li>
            </ul>
        </div>
        <div class="list-group-item">
            <div class="account-menu-header">Profile Settings</div>
            <ul class="list-unstyled">
                <li><a href="<?=URL::to('/account/edit_profile')?>"><?=t('Email, Password &amp; Notification')?></a></li>
                <li><a href="<?=URL::to('/account/github')?>"><?=t('GitHub')?></a></li>
                <li><a href="<?=URL::to('/account/linkedin')?>"><?=t('LinkedIn')?></a></li>
                <li><a href="<?=URL::to('/account/stackoverflow')?>"><?=t('StackOverflow')?></a></li>
            </ul>
        </div>
        <div class="list-group-item">
            <?=$logoutUrl?>
        </div>
    </div>
</div>
