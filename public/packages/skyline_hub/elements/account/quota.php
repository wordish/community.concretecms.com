<?php

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * @var $hostingSite \PortlandLabs\Skyline\Entity\Site
 */

$numberHelper = new \Concrete\Core\Utility\Service\Number();
?>

<?php if ($hostingSite->getBytesUsed() !== null) {

    $progressBarClass = 'bg-info';
    $maxBytes = $hostingSite->getBytesQuota();
    $currentBytes = $hostingSite->getBytesUsed();
    if ($currentBytes > $maxBytes) {
        $progressBarClass = 'bg-danger';
        $width = '100%';
    } else {
        $width = round($currentBytes / $maxBytes, 2) * 100 . '%';
    }

    ?>
        <div class="progress">
            <div class="progress-bar <?=$progressBarClass?>" role="progressbar"
                 style="width: <?=$width?>"></div>
        </div>
        <div class="text-muted mt-3"><?=t('Currently using <b>%s</b> of <b>%s</b>',
                                          $numberHelper->formatSize($currentBytes), $numberHelper->formatSize($maxBytes)
            )?></div>
    </div>

<?php } ?>
