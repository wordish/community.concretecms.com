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

class SubscriptionStatusField extends AbstractField
{
    protected $requestVariables = [
        'subscriptionStatus'
    ];
    
    public function getKey()
    {
        return 'subscriptionStatus';
    }
    
    public function getDisplayName()
    {
        return t('Subscription Status');
    }
    
    /**
     * @param SiteList $list
     * @noinspection PhpDocSignatureInspection
     */
    public function filterList(ItemList $list)
    {
        if ($this->data['subscriptionStatus'] !== '') {
            $list->filterBySubscriptionStatus($this->data['subscriptionStatus']);
        }
    }
    
    public function renderSearchField()
    {
        $app = Application::getFacadeApplication();
        /** @var Form $form */
        $form = $app->make(Form::class);
        return $form->select('subscriptionStatus', [
            '' => t('** All'),
            'incomplete' => t('Incomplete'),
            'incomplete_expired' => t('Incomplete Expired'),
            'trialing' => t('Trialing'),
            'active' => t('Active'),
            'past_due' => t('Past Due'),
            'canceled' => t('Canceled'),
            'unpaid' => t('Unpaid'),
        ], $this->data['subscriptionStatus']);
    }
}
