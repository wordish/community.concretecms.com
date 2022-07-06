<?php

defined('C5_EXECUTE') or die('Access denied');

if (isset($error) && $error->has()) { ?>
    <div class="alert alert-danger mb-5">
        <?php foreach ($error->getList() as $errorItem) { ?>
            <div><?=$errorItem?></div>
        <?php } ?>
    </div>
<?php
}
?>

<?php
if ($u->isRegistered()) { ?>


<form method="post" action="<?=$view->action('create_site')?>">
    <?=$token->output('create_site')?>

    <p class="text-center mb-5"><big><?=t("You're logged in as <b>%s</b>. %s to make this site with a different user account.",
            $u->getUserName(), $navigation->getLogInOutLink())?></big></p>

    <div class="card p-5 mb-5">

        <div class="form-group">
            <p><?=t('Give this project a name so you can find it in your profile later.')?></p>
            <div class="d-md-flex align-items-center text-center">
                <input type="text" name="hosting_site_name" class="form-control-lg form-control" required placeholder="www.mysite.com">
                <button class="ml-md-3 mt-md-0 mt-3 btn btn-xs-block btn-primary text-nowrap" type="submit"><?=t('Create Site')?></button>
            </div>
        </div>

    </div>

</form>

<?php } else { ?>

    <p class="text-center mb-5"><big><?=t("You must <a href='%s'>sign in</a> to create a site.", URL::to('/login'))?></big></p>

<?php } ?>
