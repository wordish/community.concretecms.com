<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */

namespace PortlandLabs\Skyline\Search\Site\Field\Field;

use Concrete\Core\Form\Service\Form;
use Concrete\Core\Search\Field\AbstractField;
use Concrete\Core\Search\ItemList\ItemList;
use Concrete\Core\Support\Facade\Application;
use PortlandLabs\Skyline\Site\SiteList;

class SubscriptionIdField extends AbstractField
{
    protected $requestVariables = [
        'subscriptionId'
    ];
    
    public function getKey()
    {
        return 'subscriptionId';
    }
    
    public function getDisplayName()
    {
        return t('Subscription ID');
    }
    
    /**
     * @param SiteList $list
     * @noinspection PhpDocSignatureInspection
     */
    public function filterList(ItemList $list)
    {
        $list->filterBySubscriptionId($this->data['subscriptionId']);
    }
    
    public function renderSearchField()
    {
        $app = Application::getFacadeApplication();
        /** @var Form $form */
        $form = $app->make(Form::class);
        return $form->text('subscriptionId', $this->data['subscriptionId']);
    }
}
