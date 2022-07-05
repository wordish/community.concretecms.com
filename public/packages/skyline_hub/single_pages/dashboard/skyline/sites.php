<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

defined('C5_EXECUTE') or die('Access denied');

/** @noinspection DuplicatedCode */

use Concrete\Core\Application\UserInterface\ContextMenu\MenuInterface;
use Concrete\Core\Support\Facade\Url;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Search\Site\Result\Column;
use PortlandLabs\Skyline\Search\Site\Result\Item;
use PortlandLabs\Skyline\Search\Site\Result\ItemColumn;
use PortlandLabs\Skyline\Search\Site\Result\Result;
use PortlandLabs\Skyline\Menu\SiteMenu;

/** @var string|null $class */
/** @var MenuInterface $menu */
/** @var Result $result */

?>

<div id="ccm-search-results-table">
    <table class="ccm-search-results-table" data-search-results="hosting_sites">
        <thead>
        <tr>

            <?php
            foreach ($result->getColumns() as $column): ?>
                <?php
                /** @var Column $column */ ?>
                <th class="<?php
                echo $column->getColumnStyleClass() ?>">
                    <?php
                    if ($column->isColumnSortable()): ?>
                        <a href="<?php
                        echo $column->getColumnSortURL() ?>">
                            <?php
                            echo $column->getColumnTitle() ?>
                        </a>
                    <?php
                    else: ?>
                        <span>
                                <?php
                                echo $column->getColumnTitle() ?>
                            </span>
                    <?php
                    endif; ?>
                </th>
            <?php
            endforeach; ?>
        </tr>
        </thead>

        <tbody>
        <?php
        foreach ($result->getItems() as $item) { ?>
            <?php
            /** @var Item $item */
            /** @var Site $site */
            $site = $item->getItem();
            ?>
            <tr data-details-url="<?= $item->getDetailsUrl() ?>">
                <?php
                foreach ($item->getColumns() as $column) {
                    $class = $column->getColumn(
                    ) instanceof \PortlandLabs\Skyline\Search\Site\ColumnSet\Column\NameColumn ?
                        'ccm-search-results-name' : '';
                    ?>
                    <?php
                    /** @var ItemColumn $column */ ?>
                    <td class="<?= $class ?>">
                        <?php
                        echo $column->getColumnValue(); ?>
                    </td>
                <?php
                } ?>

            </tr>
        <?php
        } ?>
        </tbody>
    </table>
</div>

<?php
echo $result->getPagination()->renderView('dashboard'); ?>
