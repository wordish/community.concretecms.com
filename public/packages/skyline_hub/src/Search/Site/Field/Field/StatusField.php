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
use PortlandLabs\Skyline\Site\StatusMap;

class StatusField extends AbstractField
{
    protected $requestVariables = [
        'status'
    ];
    
    public function getKey()
    {
        return 'status';
    }
    
    public function getDisplayName()
    {
        return t('Status');
    }
    
    /**
     * @param SiteList $list
     * @noinspection PhpDocSignatureInspection
     */
    public function filterList(ItemList $list)
    {
        if ($this->data['status'] != '') {
            $list->filterByStatus($this->data['status']);
        }
    }
    
    public function renderSearchField()
    {
        $app = Application::getFacadeApplication();
        /** @var Form $form */
        $form = $app->make(Form::class);
        $statusMap = StatusMap::getMap();
        return $form->select('status', ['' => t('** All')] + $statusMap, $this->data['status']);
    }
}
