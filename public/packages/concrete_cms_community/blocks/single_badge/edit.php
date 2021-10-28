<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Block\View\BlockView;

/** @var BlockView $view */
/** @var array $badgeHandles */
/** @var string $badgeHandle */

?>

<div class="form-group">
    <?php echo $form->label("badgeHandle", t('Badge to Display')); ?>
    <?php echo $form->select("badgeHandle", $badgeHandles, $badgeHandle) ?>
</div>

<script>
    $('#badgeHandle').selectpicker({
            liveSearch: true
        });
</script>