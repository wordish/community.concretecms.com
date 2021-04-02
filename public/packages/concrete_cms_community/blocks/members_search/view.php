<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Attribute\AttributeKeyInterface;
use Concrete\Core\Attribute\Category\CategoryService;
use Concrete\Core\Attribute\Context\FrontendFormContext;
use Concrete\Core\Attribute\Form\Renderer;
use Concrete\Core\Attribute\Form\RendererBuilder;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Localization\Service\Date;
use Concrete\Core\Page\Page;
use Concrete\Core\Search\Pagination\Pagination;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\UserInfo;
use Concrete\Core\Utility\Service\Text;

/** @var Pagination $pagination */
/** @var array $selectedAttributeKeys */
/** @var bool $displayCertificationFilter */
/** @var array $selectedCertificationsTests */
/** @var array $certificationTestOptions */
/** @var array $sortByOptions */
/** @var string $sortBy */
/** @var string $q */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var Text $text */
$text = $app->make(Text::class);
/** @var Date $date */
$date = $app->make(Date::class);
/** @var CategoryService $categoryService */
$categoryService = $app->make(CategoryService::class);
$userCategoryEntity = $categoryService->getByHandle("user");
$userCategory = $userCategoryEntity->getAttributeKeyCategory();

/** @var UserInfo[] $results */
$results = $pagination->getCurrentPageResults();

$profileFormRenderer = new Renderer(
    new FrontendFormContext()
);
?>

<div class="members-search">
    <div class="card">
        <div class="card-body">
            <div>
                <form action="#" method="get">
                    <div class="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="ccm-search-input">
                                            <?php echo $form->text("q", $q, ["placeholder" => t("Search Developers"), "class" => "search-control"]); ?>
                                        </div>

                                        <div class="input-group-append">
                                            <div class="dropdown position-static">
                                                <button type="button" class="btn btn-secondary toggle-dropdown" id="toggleFilters">
                                                    <span class="inner-text">
                                                        <?php echo t("Filters"); ?>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="float-md-right float-lg-right float-xl-right float-sm-right float-xs-none">
                                    <div class="form-group form-inline sort-by">
                                        <?php echo $form->label("sortBy", t("Sort By")); ?>

                                        <div class="input-group">
                                            <?php echo $form->select("sortBy", $sortByOptions, $sortBy); ?>

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary btn-search">
                                                    <?php echo t("Search"); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ccm-dropdown-menu d-none">
                        <div class="row">
                            <?php if ($displayCertificationFilter) { ?>
                                <div class="col">
                                    <h3>
                                        <?php echo t("Certification"); ?>
                                    </h3>

                                    <?php foreach ($certificationTestOptions as $testId => $testName) { ?>
                                        <div class="form-check">
                                            <?php echo $form->checkbox("certification[" . $testId . "]", 1, $selectedCertificationsTests[$testId], ["class" => "form-check-input", "id" => "ccm-test-" . $testId]); ?>
                                            <?php echo $form->label("ccm-test-" . $testId, $testName, ["class" => "form-check-label"]); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <?php foreach ($selectedAttributeKeys as $akHandle) { ?>
                                <?php $attributeKey = $userCategory->getAttributeKeyByHandle($akHandle); ?>

                                <?php if ($attributeKey instanceof AttributeKeyInterface) { ?>
                                    <div class="col">
                                        <h3>
                                            <?php echo $attributeKey->getAttributeKeyDisplayName(); ?>
                                        </h3>

                                        <?php /** @noinspection PhpUndefinedMethodInspection */
                                        /** @var RendererBuilder $view */
                                        $view = $profileFormRenderer->buildView($attributeKey);
                                        /** @noinspection PhpUndefinedMethodInspection */
                                        $view->setSupportsLabel(false);
                                        /** @noinspection PhpUndefinedMethodInspection */
                                        $view->setIsRequired(false);
                                        $view->render();
                                        ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </form>

                <?php if (count($results) === 0) { ?>
                    <p>
                        <?php echo t("No users found. Click %s to reset all search parameters.", sprintf(
                            "<a href=\"%s\">%s</a>",
                            (string)Url::to(Page::getCurrentPage()),
                            t("here")
                        )); ?>
                    </p>
                <?php } else { ?>
                    <div class="container search-results">
                        <?php foreach ($results as $result) { ?>
                            <div class="member row">
                                <div class="avatar col">
                                    <div class="image-wrapper">
                                        <a href="<?php echo (string)Url::to("/members/profile", $result->getUserID()); ?>">
                                            <?php echo $result->getUserAvatar()->output(); ?>
                                        </a>
                                    </div>
                                </div>

                                <div class="col">
                                    <a href="<?php echo (string)Url::to("/members/profile", $result->getUserID()); ?>">
                                        <h2 class="username">
                                            <?php echo $result->getUserName(); ?>
                                        </h2>
                                    </a>

                                    <div class="description">
                                        <?php
                                        $description = $text->shorten(strip_tags((string)$result->getAttribute("description")));
                                        if ($description == "") {
                                            $description = t("No description available");
                                        }

                                        echo $description;
                                        ?>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="info">
                                        <?php /** @noinspection PhpUnhandledExceptionInspection */
                                        echo t("Joined %s", $date->formatDate($result->getUserDateAdded())); ?>
                                    </div>

                                    <a href="javascript:void(0);" class="btn btn-secondary send-message"
                                       data-receiver="<?php echo h($result->getUserID()); ?>">
                                        <?php echo t("Contact User"); ?>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="pagination-container">
                        <?php
                        $pages = $pagination->getCurrentPageResults();
                        if ($pagination->haveToPaginate()) {
                            $showPagination = true;
                            echo $pagination->renderView('application');
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>