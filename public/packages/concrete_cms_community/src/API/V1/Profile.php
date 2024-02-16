<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\Community\API\V1;

use Concrete\Core\Http\Request;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\User\UserInfo;
use Concrete\Core\User\UserInfoRepository;

class Profile
{
    /**
     * @return bool|Response|\Concrete\Core\Routing\RedirectResponse|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function lookupEmail()
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
            return $responseFactory->redirect((string) Url::to("/members/profile/", $userInfo->getUserID()), Response::HTTP_TEMPORARY_REDIRECT);
        } else {
            return $responseFactory->notFound(Page::getCurrentPage());
        }
    }
}
