<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<a href="<?=$view->action('connect/' . $valt->generate('stackoverflow_connect'))?>" class="btn-lg btn-primary btn">
    <?= $id ? t('Refresh Account') : t('Connect Account') ?>
</a>

<?php
if ($id) {
    ?>
    <ul>
        <li><strong>Bronze Badges:</strong> <?= intval($bronze ?? 0) ?></li>
        <li><strong>Silver Badges:</strong> <?= intval($silver ?? 0) ?></li>
        <li><strong>Gold Badges:</strong> <?= intval($gold ?? 0) ?></li>
        <li><strong>Reputation:</strong> <?= intval($reputation ?? 0) ?></li>
    </ul>
    <?php
}
