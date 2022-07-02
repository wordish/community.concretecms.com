<?php

defined('C5_EXECUTE') or die('Access Denied.');

View::element('account/breadcrumb', [], 'skyline_hub');

?>


<main>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="page-header">
                    <h1 class="page-title mt-0"><?=t('Installing Concrete...')?></h1>
                </div>
                <div class="card">
                    <div class="card-body" vue-skyline>
                        <skyline-installation-progress :site='<?=json_encode($hostingSite)?>'></skyline-installation-progress>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
