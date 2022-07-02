<?php

use Concrete\Core\Navigation\Breadcrumb\PageBreadcrumbFactory;

defined('C5_EXECUTE') or die('Access Denied.');

// I know this is dumb, the breadcrumb shouldn't be added on this page. But it's hidden from the template in the theme
// by looking for /account, and rather than mess with that logic I'll just add it explicitly on these templates

$page = Page::getCurrentPage();

$factory = app(PageBreadcrumbFactory::class);
$factory->setIncludeCurrent(false);
$factory->setIgnoreExcludeNav(true);

$breadcrumb = $factory->getBreadcrumb($page);

if ($breadcrumb && count($breadcrumb->getItems()) > 0) {
    ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="breadcrumb-navigation">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <?php
                        /** @var \Concrete\Core\Navigation\Item\Item $item */
                        foreach ($breadcrumb->getItems() as $item) {
                            if ($item->isActive()) {
                                ?>
                                <li class="breadcrumb-item active" aria-current="page"><?= h($item->getName()) ?></li>
                                <?php
                            } else {
                                ?>
                                <li class="breadcrumb-item"><a href="<?= h($item->getUrl()) ?>"><?= h($item->getName()) ?></a></li>
                                <?php
                            }
                        }
                        ?>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
    <?php
}

?>

