<?php

defined('C5_EXECUTE') or die('Access denied');


?>

<div id="ccm-search-results-table">
    <table class="ccm-search-results-table" data-search-results="pages">
        <thead>
        <tr>
            <th><span><?=t('Version')?></span></th>
            <th><span><?=t('Date Released')?></span></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($releases as $release) { ?>
            <?php
            /** @var \PortlandLabs\Hosting\Software\ConcreteRelease $release */
            ?>
            <tr data-details-url="<?=URL::to('/dashboard/concrete_cms_community/releases', 'view_details', $release->getId())?>">
                <td class="ccm-search-results-name"><?=$release->getVersion()?></td>
                <td><?=$release->getDateReleasedString()?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<div class="ccm-dashboard-header-buttons">
    <ul class="ccm-dashboard-header-icons">
        <li>
            <a href="<?=$view->action('add_release')?>" class="btn btn-sm btn-secondary">
                <?=t('Add Release')?> <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
        </li>
    </ul>
</div>