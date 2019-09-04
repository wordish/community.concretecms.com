<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<a href="<?=$view->action('connect/' . $valt->generate('github_connect'))?>" class="btn-lg btn-primary btn">
    <?= $id ? t('Refresh Account') : t('Connect Account') ?>
</a>
