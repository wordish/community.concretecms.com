<?php

defined('C5_EXECUTE') or die('Access denied');

?>

    <div id="ccm-search-results-table">
        <table class="ccm-search-results-table" data-search-results="pages">
            <thead>
            <tr>

                <?php foreach ($result->getColumns() as $column) { ?>
                    <?php /** @var Column $column */ ?>
                    <th class="<?php echo $column->getColumnStyleClass(); ?>">
                        <?php if ($column->isColumnSortable()) { ?>
                            <a href="<?php echo $column->getColumnSortURL(); ?>">
                                <?php echo $column->getColumnTitle(); ?>
                            </a>
                        <?php } else { ?>
                            <span>
                            <?php echo $column->getColumnTitle(); ?>
                        </span>
                        <?php } ?>
                    </th>
                <?php } ?>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($result->getItems() as $item) { ?>
                <?php
                /** @var \PortlandLabs\Hosting\Project\Search\Result\Item $item */
                /** @var \PortlandLabs\Hosting\Project\Project $project */
                $project = $item->getItem();
                ?>
                <tr data-details-url="<?=URL::to('/dashboard/hosting/projects', 'view_details', $project->getId())?>">

                    <?php foreach ($item->getColumns() as $column) { ?>
                        <?php /** @var ItemColumn $column */ ?>
                        <td class="<?php echo $class; ?>">
                            <?php echo $column->getColumnValue(); ?>
                        </td>
                    <?php } ?>

                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>


<?php echo $result->getPagination()->renderView('dashboard'); ?>