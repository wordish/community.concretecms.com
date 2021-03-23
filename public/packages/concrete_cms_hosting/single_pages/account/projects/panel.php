<?php

defined('C5_EXECUTE') or die('Access denied');

?>

<div class="container">
    <div class="row">
        <div class="col" vue-hosting v-cloak>

            <hosting-control-panel
                    :project="<?=h($projectJson)?>"
                    :projects="<?=h($projects)?>"
            ></hosting-control-panel>


        </div>
    </div>
</div>
