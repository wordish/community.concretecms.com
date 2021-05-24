<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\ConcreteCmsCommunity\Controller\SinglePage\Dashboard\ConcreteCmsCommunity;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Tree\Node\Type\GroupFolder;
use Concrete\Core\Tree\Type\Group as GroupTree;
use Concrete\Core\User\Group\GroupType;
use PortlandLabs\Community\TeamsService;
use Symfony\Component\HttpFoundation\Response;

class Settings extends DashboardPageController
{
    public function updated()
    {
        $this->set('success', t("The settings has been successfully updated."));
        $this->setDefaults();
    }

    private function setDefaults()
    {
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);
        /** @var TeamsService $teamsService */
        $teamsService = $this->app->make(TeamsService::class);

        $tree = GroupTree::get();
        $this->set('tree', $tree);
        $this->set('submitKarmaRequestPage', $config->get("concrete_cms_community.submit_karma_request_page", 0));
        $this->set('teamsGroupFolderId', $teamsService->getTeamsGroupFolder() instanceof GroupFolder ? $teamsService->getTeamsGroupFolder()->getTreeNodeID() : 0);
        $this->set('teamsGroupTypeId', $teamsService->getTeamsGroupType() instanceof GroupType ? $teamsService->getTeamsGroupType()->getId() : 0);

        // Discourse settings

        $availableDiscourseEventTypes = [
            "user_registered",
            "user_activated",
            "trust_level_changed",
            "message_sent",
            "topic_created",
            "topic_liked",
            "topic_pinned",
            "topic_unpinned",
            "topic_archived",
            "topic_closed",
            "topic_invisibled",
            "post_liked",
            "post_created",
            "reply_posted",
            "auto_close_set",
            "flag_raised"
        ];

        $this->set('discourseEndpoint', $config->get("concrete_cms_community.discourse.endpoint", ''));
        $this->set('discourseApiKey', $config->get("concrete_cms_community.discourse.api_key", ''));
        $this->set('discourseAchievementsMapping', $config->get("concrete_cms_community.discourse.achievements_mapping", []));
        $this->set('availableDiscourseEventTypes',$availableDiscourseEventTypes);
    }

    public function view()
    {
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $this->app->make(ResponseFactory::class);
        /** @var TeamsService $teamsService */
        $teamsService = $this->app->make(TeamsService::class);

        if ($this->request->getMethod() === "POST") {
            if ($this->token->validate("update_settings")) {
                $config->save("concrete_cms_community.submit_karma_request_page", (int)$this->request->request->get("submitKarmaRequestPage"));

                $config->save("concrete_cms_community.discourse.endpoint", (string)$this->request->request->get("discourseEndpoint"));
                $config->save("concrete_cms_community.discourse.api_key", (string)$this->request->request->get("discourseApiKey"));
                $config->save("concrete_cms_community.discourse.achievements_mapping", $this->request->request->get("discourseAchievementsMapping", []));

                $teamsService->setTeamsGroupFolder(GroupFolder::getByID($this->request->request->get("teamsGroupFolderId")));
                $teamsService->setTeamsGroupType(GroupType::getByID($this->request->request->get("teamsGroupTypeId")));
                return $responseFactory->redirect(Url::to("/dashboard/concrete_cms_community/settings/updated"), Response::HTTP_TEMPORARY_REDIRECT);
            } else {
                $this->error->add($this->token->getErrorMessage());
            }
        }

        $this->setDefaults();
    }

}
