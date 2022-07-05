<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */

namespace PortlandLabs\Skyline\Search\Site\Field;

use Concrete\Core\Search\Field\Manager as FieldManager;
use PortlandLabs\Skyline\Search\Site\Field\Field\NameField;
use PortlandLabs\Skyline\Search\Site\Field\Field\HandleField;
use PortlandLabs\Skyline\Search\Site\Field\Field\SubscriptionIdField;
use PortlandLabs\Skyline\Search\Site\Field\Field\SubscriptionStatusField;
use PortlandLabs\Skyline\Search\Site\Field\Field\NeighborhoodField;
use PortlandLabs\Skyline\Search\Site\Field\Field\AdminPasswordField;
use PortlandLabs\Skyline\Search\Site\Field\Field\StatusField;
use PortlandLabs\Skyline\Search\Site\Field\Field\SuspendedTimestampField;

class Manager extends FieldManager
{
    
    public function __construct()
    {
        $properties = [
            new NameField(),
            new HandleField(),
            new SubscriptionIdField(),
            new SubscriptionStatusField(),
            new NeighborhoodField(),
            new AdminPasswordField(),
            new StatusField(),
            new SuspendedTimestampField(),
        ];
        $this->addGroup(t('Core Properties'), $properties);
    }
}
