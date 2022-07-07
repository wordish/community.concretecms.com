<?php

namespace PortlandLabs\Skyline\Site;

use PortlandLabs\Skyline\Entity\Site;

class StatusMap
{

    public static function getMap(): array
    {
        return [
            Site::STATUS_INSTALLING => t('Installation in Progress'),
            Site::STATUS_ACTIVE => t('Site installed and running'),
            Site::STATUS_REINSTATING => t('Reinstating'),
            Site::STATUS_SUSPENDED_TRIAL_CANCELLED => t('Site suspended (trial cancelled)'),
            Site::STATUS_SUSPENDED_UNPAID => t('Site suspended (past due/unpaid)'),
            Site::STATUS_SUSPENDED_ADMIN_SUSPENDED => t('Site suspended (administrative action)'),
            Site::STATUS_DELETED_REMOVAL_IMMINENT => t('Site deleted – removal imminent.'),
        ];
    }
}