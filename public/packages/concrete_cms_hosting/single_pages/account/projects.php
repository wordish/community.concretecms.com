<?php

defined('C5_EXECUTE') or die('Access denied');

?>

<div class="container">
    <div class="row">
        <div class="col">

            <h1 class="highlight"><?=t('Hosting')?></h1>

            <div class="card">
                <div class="card-body">

                    <table class="table">
                        <thead>
                        <tr>
                            <th><?=t('Name')?></th>
                            <th><?=t('Site Type')?></th>
                        </tr>
                        </thead>

                        <?php

                        foreach ($pagination->getCurrentPageResults() as $project) { ?>

                            <tr>
                                <td><a href="<?=URL::to('/account/projects', 'panel', $project->getId())?>"><?=$project->getName()?></a></td>
                                <td><?=$project->getSiteTypeString()?></a></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

            <?php
            if ($pagination->haveToPaginate()) { ?>

                <div class="ccm-search-results-pagination">
                    <?= $paginationView->render(
                        $pagination,
                        function ($page) use ($currentPage) {
                            $url = Url::to($currentPage);
                            return $url . '?page=' . $page;
                        }
                    ); ?>
                </div>

                <?php
            } ?>

        </div>
    </div>
</div>
