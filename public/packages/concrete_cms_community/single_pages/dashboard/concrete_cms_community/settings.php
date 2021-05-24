<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Form\Service\Widget\PageSelector;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Validation\CSRF\Token;

/** @var int $submitKarmaRequestPage */
/** @var int $teamsGroupFolderId */
/** @var int $teamsGroupTypeId */
/** @var Concrete\Core\Tree\Tree $tree */
/** @var string $discourseEndpoint */
/** @var string $discourseApiKey */
/** @var array $discourseAchievementsMapping */
/** @var array $discourseCommunityPointsMapping */
/** @var array $availableDiscourseEventTypes */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var PageSelector $pageSelector */
$pageSelector = $app->make(PageSelector::class);
/** @var Token $token */
$token = $app->make(Token::class);

?>

<form action="#" method="post">
    <?php echo $token->output("update_settings"); ?>

    <fieldset>
        <legend>
            <?php echo t("General"); ?>
        </legend>

        <div class="form-group">
            <?php echo $form->label("submitKarmaRequestPage", t("Submit Karma Request Page")); ?>
            <?php echo $pageSelector->selectPage("submitKarmaRequestPage", $submitKarmaRequestPage); ?>
        </div>
    </fieldset>

    <fieldset>
        <legend>
            <?php echo t("Teams"); ?>
        </legend>

        <div class="form-group">
            <?php echo $form->label("teamsGroupTypeId", t("Group Type")); ?>
            <?php echo $form->select("teamsGroupTypeId", \Concrete\Core\User\Group\GroupType::getSelectList(), $teamsGroupTypeId); ?>
        </div>

        <div class="form-group">
            <label class="control-label">
                <?php echo t('Parent Folder') ?>
            </label>

            <div class="controls">
                <div class="groups-tree" style="width: 460px" data-groups-tree="<?php echo $tree->getTreeID() ?>"></div>
                <?php echo $form->hidden('teamsGroupFolderId') ?>
                <script type="text/javascript">
                    $(function () {
                        $('[data-groups-tree=<?php echo $tree->getTreeID()?>]').concreteTree({
                            'treeID': '<?php echo $tree->getTreeID()?>',
                            'chooseNodeInForm': 'single',
                            'enableDragAndDrop': false,
                            ajaxData: {
                                displayOnly: 'group_folder'
                            },
                            'selectNodesByKey': [<?php echo intval($teamsGroupFolderId)?>],
                            'onSelect': function (nodes) {
                                if (nodes.length) {
                                    $('input[name=teamsGroupFolderId]').val(nodes[0]);
                                } else {
                                    $('input[name=teamsGroupFolderId]').val('');
                                }
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>
            <?php echo t("Discourse Credentials"); ?>
        </legend>

        <p>
            <?php echo t("Please go to your discourse installation and create an api key. They api integration is required for retrieve advanced user details from discourse. After you have created the api key you need to add the credentials here in the fields below."); ?>
        </p>

        <p>
            <?php echo t("After you have configured up the api connection you need to setup a webhook in discourse. Please forward all requests to the following api address: <code>%s</code>", (string)Url::to("/api/v1/discourse/handle_webhook_event")); ?>
        </p>

        <div class="form-group">
            <?php echo $form->label("discourseEndpoint", t("Endpoint")); ?>
            <?php echo $form->text('discourseEndpoint', $discourseEndpoint, ["placeholder" => "Example https://forums.concretecms.com..."]); ?>
        </div>

        <div class="form-group">
            <?php echo $form->label("discourseApiKey", t("Api Key")); ?>
            <?php echo $form->password('discourseApiKey', $discourseApiKey); ?>
        </div>
    </fieldset>

    <fieldset>
        <legend>
            <?php echo t("Discourse Webhooks Mapping"); ?>
        </legend>

        <p>
            <?php echo t("In this section you can map the received event types from discourse with an achievements. For this you just need to enter a achievement handle. Leave the fields empty if you don't want to assign an achievement for the associated event type."); ?>
        </p>

        <?php foreach ($availableDiscourseEventTypes as $availableDiscourseEventType) { ?>
            <div class="form-group">
                <?php echo $form->label($availableDiscourseEventType, t("Achievement and community points for discourse event <code>%s</code>", $availableDiscourseEventType)); ?>

                <div class="row">
                    <div class="col-sm-6">
                        <?php echo $form->text("discourseAchievementsMapping[" . $availableDiscourseEventType . "]", $discourseAchievementsMapping[$availableDiscourseEventType], [
                            "id" => $availableDiscourseEventType,
                            "placeholder" => t("Enter an achievement handle or leave empty...")
                        ]); ?>
                    </div>

                    <div class="col-sm-6">
                        <?php echo $form->text("discourseCommunityPointsMapping[" . $availableDiscourseEventType . "]", $discourseCommunityPointsMapping[$availableDiscourseEventType], [
                            "placeholder" => t("Enter the amount of community points or leave empty...")
                        ]); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </fieldset>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <div class="float-right">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> <?php echo t("Save"); ?>
                </button>
            </div>
        </div>
    </div>
</form>