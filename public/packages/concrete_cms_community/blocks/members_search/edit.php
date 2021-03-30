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
/** @var array $availableAttributes */
/** @var array $selectedAttributeKeys */

?>

<h3>
    <?php echo t("Searchable User Attributes"); ?>
</h3>

<div class="form-group">
    <?php foreach ($availableAttributes as $akHandle => $attributeDisplayName) { ?>
        <div class="form-check">
            <?php echo $form->checkbox("selectedAttributeKeys[]", $akHandle, $selectedAttributeKeys[$akHandle], ["class" => "form-check-input", "id" => $akHandle]); ?>
            <?php echo $form->label($akHandle, $attributeDisplayName, ["class" => "form-check-label"]); ?>
        </div>
    <?php } ?>
</div>
