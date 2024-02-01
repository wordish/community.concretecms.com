<?php

namespace PortlandLabs\Community\API\V1;

use Concrete\Core\Api\Controller\Users as CoreApiUsers;
use Concrete\Core\Api\Fractal\Transformer\UserTransformer;
use Concrete\Core\Api\Resources;
use Concrete\Core\User\UserInfoRepository;

/**
 * This custom user controller is basically copied from the core user controller - but it omits permission checking
 * because we need to be able to view users without having a dedicated "authorized user" in the API. You still have to
 * have a valid API connection with the users:read scope.
 */
class Users extends CoreApiUsers
{

    public function read($uID)
    {
        $uID = (int) $uID;
        $repository = $this->app->make(UserInfoRepository::class);
        $user = $repository->getByID($uID);
        if (!$user) {
            return $this->error(t('User not found.'), 404);
        }

        $userTransformer = $this->app->make(UserTransformer::class);
        $userTransformer->setDefaultIncludes([Resources::RESOURCE_CUSTOM_ATTRIBUTES]);
        return $this->transform($user, $userTransformer, Resources::RESOURCE_USERS);
    }


}