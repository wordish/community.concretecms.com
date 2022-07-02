<?php

defined('C5_EXECUTE') or die('Access Denied.');


View::element('account/breadcrumb', [], 'skyline_hub');

?>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="page-header">
                    <h1 class="page-title mt-0"><?=$hostingSite->getName()?></h1>
                </div>
                <div class="card">
                    <div class="card-body">
                        <?php
                        View::element('account/site_table', ['hostingSites' => [$hostingSite]], 'skyline_hub');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
