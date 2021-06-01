<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\ConcreteCmsCommunity\Controller\SinglePage\Members;

use Concrete\Controller\SinglePage\Members\Profile as CoreProfile;
use Concrete\Core\Application\EditResponse;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\Request;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Page\Page;
use Concrete\Core\Routing\RedirectResponse;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\User;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\UserInfoRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Uri;
use Symfony\Component\HttpFoundation\JsonResponse;

class Profile extends CoreProfile
{
    /**
     * This method redirects the user to the associated discourse edit profile page
     *
     * @return RedirectResponse|JsonResponse
     */
    public function edit_forum_info()
    {
        $currentUser = new User();
        $app = Application::getFacadeApplication();
        /** @var Repository $config */
        $config = $app->make(Repository::class);
        $errorList = new ErrorList();
        $discourseEndpoint = $config->get("concrete_cms_community.discourse.endpoint");
        $discourseApiKey = $config->get("concrete_cms_community.discourse.api_key");
        $baseUrl = new Uri($discourseEndpoint);
        $client = new Client();
        $discourseUsername = "";

        $apiUrl = $baseUrl
            ->withPath(
                sprintf(
                    "/u/by-external/%s.json",
                    (string)$currentUser->getUserID()
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
                $json = json_decode($rawResponse, true);

                if (isset($json["user"]["username"])) {
                    $discourseUsername = $json["user"]["username"];
                } else {
                    $errorList->add(t("Error while looking up the user details. Invalid payload."));
                }
            }  else {
                $errorList->add(t("Error while looking up the user details. Invalid status code."));
            }

        } catch (GuzzleException $e) {
            $errorList->add(t("Error while looking up the user details. Internal server error."));
        }

        if (!$errorList->has()) {
            $redirectUrl = (string)$baseUrl
                ->withPath(
                    sprintf(
                        "/u/%s/preferences/account",
                        $discourseUsername
                    )
                );

            return new RedirectResponse($redirectUrl, Response::HTTP_TEMPORARY_REDIRECT);
        }
    }

    /**
     * This method is required for Discourse to display the user profile from the account email address.
     *
     * @return bool|Response|\Concrete\Core\Routing\RedirectResponse|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function lookup_email()
    {
        $app = Application::getFacadeApplication();
        /** @var Request $request */
        $request = $app->make(Request::class);
        /** @var UserInfoRepository $userInfoRepository */
        $userInfoRepository = $app->make(UserInfoRepository::class);
        /** @var ResponseFactory $responseFactory */
        $responseFactory = $app->make(ResponseFactory::class);

        $email = $request->query->get("email", "");

        $userInfo = $userInfoRepository->getByEmail($email);

        if ($userInfo instanceof UserInfo) {
            return $responseFactory->redirect((string)Url::to("/members/profile/", $userInfo->getUserID()), Response::HTTP_TEMPORARY_REDIRECT);
        } else {
            return $responseFactory->notFound(Page::getCurrentPage());
        }
    }

    public function on_start()
    {

        $this->set('exclude_breadcrumb', true);

        return parent::on_start();
    }

}