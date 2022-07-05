<?php

defined('C5_EXECUTE') or die('Access Denied.');

?>

<main>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="page-header">
                    <h1 class="page-title"><?=t('My Sites')?></h1>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mb-4 border-bottom pb-3">
                            <form method="get" action="<?=$view->action('search')?>" class="form-inline">
                                <div class="form-group mr-sm-3 mb-2">
                                    <input type="search" name="query" value="<?=$query ?? null?>" class="form-control form-control-lg" placeholder="Search Sites">
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Search</button>
                            </form>

                            <div class="ml-auto">
                                <a href="<?=$_ENV['URL_CREATE_NEW_SITE_WIZARD']?>" class="btn btn-primary" target="_blank"><?=t('New Site')?></a>
                            </div>
                        </div>

                        <?php
                        /**
                         * @var $hostingSites \PortlandLabs\Skyline\Entity\Site[]
                         */
                        if (count($hostingSites)) {

                            View::element('account/site_table', ['hostingSites' => $hostingSites], 'skyline_hub');

                        ?>

                        <?php } else if ($view->controller->getAction() === 'search') { ?>

                            <p><?=t('No sites found.')?></p>

                        <?php } else { ?>
                            <p><?=t('You have not created any hosted Concrete CMS projects.')?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

