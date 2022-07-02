<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Account;

use Concrete\Core\Page\Controller\AccountPageController;
use PortlandLabs\Skyline\Site\SiteList;

class Hosting extends AccountPageController
{

    public function view()
    {
        $list = $this->getList();
        $this->sendListResults($list);
    }

    public function search()
    {
        $query = h($this->request->query->get('query'));
        $list = $this->getList();
        $list->filterByKeywords($query);
        $this->sendListResults($list);
        $this->set('query', $query);
    }

    protected function getList()
    {
        $profile = $this->get('profile');
        $list = $this->app->make(SiteList::class);
        $list->filterByAuthorUserID($profile->getUserID());
        $list->sortByDateAddedDescending();
        return $list;
    }

    protected function sendListResults($list)
    {
        $hostingSites = $list->getResults();
        $this->set('hostingSites', $hostingSites);
    }

}