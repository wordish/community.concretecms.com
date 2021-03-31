<?php
namespace PortlandLabs\Community\User\Point\Action;

/**
 * @deprecated
 * Class WonBadgeAction
 * @package PortlandLabs\Community\User\Point\Action
 */
class WonBadgeAction extends Action
{
    public function addDetailedEntry($user, $group)
    {
        $obj = new WonBadgeActionDescription();
        $obj->setBadgeGroupID($group->getGroupID());
        $entry = self::addEntry($user, $obj, $group->getGroupBadgeCommunityPointValue());
    }
}
