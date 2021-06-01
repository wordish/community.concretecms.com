<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection SqlDialectInspection */
/** @noinspection SqlNoDataSourceInspection */
/** @noinspection PhpMissingFieldTypeInspection */

namespace Concrete\Package\ConcreteCmsCommunity\Block\Forums;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\Response;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Uri;

class Controller extends BlockController
{
    protected $btTable = "btForums";

    public function getBlockTypeDescription()
    {
        return t('Add a forums card to your page.');
    }

    public function getBlockTypeName()
    {
        return t('Forums');
    }

    public function view()
    {
        $currentPage = Page::getCurrentPage();
        $profile = $currentPage->getPageController()->get("profile");
        $currentUser = new User();
        $isOwnProfile = false;
        $app = Application::getFacadeApplication();
        /** @var Repository $config */
        $config = $app->make(Repository::class);
        $discourseEndpoint = $config->get("concrete_cms_community.discourse.endpoint");
        $discourseApiKey = $config->get("concrete_cms_community.discourse.api_key");
        $baseUrl = new Uri($discourseEndpoint);
        $client = new Client();
        $topics = [];

        $apiUrl = $baseUrl
            ->withPath(
                sprintf(
                    "/u/by-external/%s.json",
                    (string)$profile->getUserID()
                )
            );

        try {
            $response = $client->request("GET", $apiUrl, [
                "headers" => [
                    "Api-Key" => $discourseApiKey
                ]
            ]);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                $rawResponse = $response->getBody()->getContents();
                $discourseUserData = json_decode($rawResponse, true);

                if (isset($discourseUserData["topics"])) {
                    foreach($discourseUserData["topics"] as $topic) {
                        $topics[] = [
                            "title" => $topic["title"],
                            "url" => (string)$baseUrl
                                ->withPath(
                                    sprintf(
                                        "/t/about-the-site-feedback-category/%s/%s",
                                        (string)$topic["id"],
                                        (string)$topic["posts_count"]
                                    )
                                )
                        ];
                    }
                }
            }

        } catch (GuzzleException $e) {
            // Ignore
        }

        if ($profile instanceof UserInfo) {
            if ($currentUser->isRegistered() && $profile->getUserID() == $currentUser->getUserID()) {
                $isOwnProfile = true;
            }
        }

        $this->set('profile', $profile);
        $this->set('topics', $topics);
        $this->set('discourseUserData', $discourseUserData);
        $this->set('isOwnProfile', $isOwnProfile);
    }
}
