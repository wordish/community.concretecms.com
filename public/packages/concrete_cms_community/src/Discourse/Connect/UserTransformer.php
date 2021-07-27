<?php
declare(strict_types=1);

namespace PortlandLabs\Community\Discourse\Connect;

use Concrete\Core\User\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * Transform a given user into a DiscourseConnect friendly list of attributes.
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        $userInfo = $user->getUserInfoObject();
        $avatar = $userInfo->getUserAvatar();

        return [
            'external_id' => $user->getUserID(),
            'email' => $userInfo->getUserEmail(),
            'username' => $user->getUserName(),
            'name' => collect(
                [
                    $userInfo->getAttribute('first_name'),
                    $userInfo->getAttribute('last_name'),
                ]
            )->filter()->implode(' '),
            'avatar_url' => (string)$avatar->getPath(),
            'avatar_force_update' => 'true',
            'suppress_welcome_message' => 'true',

            // Not yet used
            'title' => '',
            'website' => '',
            'location' => '',
            'groups' => '',
            'remove_groups' => '',
            'require_activation' => 'false',
            'locale' => '',
            'locale_force_update' => 'false',
            'logout' => 'false',
            'bio' => '',
            //'admin' => $user->inGroup($this->repository->getGroupById(ADMIN_GROUP_ID)),
            //'moderator' => 'false',
        ];
    }

}
