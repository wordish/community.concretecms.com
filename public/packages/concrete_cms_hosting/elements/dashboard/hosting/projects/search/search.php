<?php

defined('C5_EXECUTE') or die("Access Denied.");

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Url;

/** @var string $headerSearchAction */
/** @var Form $form */
?>

<div class="ccm-header-search-form ccm-ui" data-header="logs-manager">
    <form method="get" class="form-inline" action="<?php echo $headerSearchAction ?>">

        <div class="ccm-header-search-form-input input-group">
            <?php
                echo $form->search('keywords', [
                    'placeholder' => t('Search'),
                    'class' => 'border-right-0',
                    'autocomplete' => 'off'
                ]);
            ?>
            
            <div class="input-group-append">
                <button type="submit" class="input-group-icon">
                    <svg width="16" height="16">
                        <use xlink:href="#icon-search"/>
                    </svg>
                </button>
            </div>
        </div>
    </form>
</div>