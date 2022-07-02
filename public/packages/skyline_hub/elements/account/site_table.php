<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var $hostingSite \PortlandLabs\Skyline\Site\Site[]
 */
?>

<table class="table">
    <thead>
    <tr>
        <th class="pt-0 pb-3 border-bottom"><?=t('NAME')?></th>
        <th class="pt-0 pb-3 border-bottom"><?=t('URL')?></th>
        <th class="pt-0 pb-3 border-bottom"><?=t('DATE CREATED')?></th>
        <th class="pt-0 text-center pt-3 pb-3 border-bottom"><?=t('STATUS')?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($hostingSites as $hostingSite) { ?>
        <tr>
            <td><a href="<?=$hostingSite->getControlPanelUrl()?>"><?=$hostingSite->getName()?></a></td>
            <td><a href="<?=$hostingSite->getPublicUrl()?>" target="_blank"><?=$hostingSite->getPublicUrl()?></a></td>
            <td><?=$hostingSite->getDateAdded()->format('F d, Y')?></td>
            <td class="text-center"><?=$hostingSite->getStatusBadge()?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>

