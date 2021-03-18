<?php

use Concrete\Core\Utility\Service\Url;
use Concrete\Core\Support\Facade\Url as UrlFacade;

defined('C5_EXECUTE') or die("Access Denied.");

/** @var $urlHelper Url */
?>

<div class="form-inline">
    <?php if (!empty($itemsPerPageOptions)): ?>
        <div class="btn-group">
            <button
                    type="button"
                    class="btn btn-secondary p-2 dropdown-toggle"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">

                <span id="selected-option">
                    <?php echo $itemsPerPage; ?>
                </span>
            </button>

            <ul class="dropdown-menu">
                <li class="dropdown-header">
                    <?php echo t('Items per page') ?>
                </li>

                <?php foreach ($itemsPerPageOptions as $itemsPerPageOption): ?>
                    <?php
                    $url = $urlHelper->setVariable([
                        'itemsPerPage' => $itemsPerPageOption
                    ]);
                    ?>

                    <li data-items-per-page="<?php echo $itemsPerPageOption; ?>">
                        <a class="dropdown-item <?php echo ($itemsPerPageOption === $itemsPerPage) ? 'active' : ''; ?>"
                           href="<?php echo $url ?>">
                            <?php echo $itemsPerPageOption; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <ul class="ccm-dashboard-header-icons">
        <li>
            <a class="btn btn-sm" title="<?php echo h(t('Add Project')) ?>"
               href="<?php echo (string)UrlFacade::to("/dashboard/hosting/projects", "add"); ?>">
                <?=t("Add Project")?> <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
        </li>
    </ul>
</div>

