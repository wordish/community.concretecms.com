<?php

namespace Concrete\Package\ConcreteCmsHosting\Block\HostingProjectList;

use Concrete\Core\Block\BlockController;
use GuzzleHttp\Client;

class Controller extends BlockController
{
    public function getBlockTypeDescription()
    {
        return t('Lists hosting projects for a logged-in user.');
    }

    public function getBlockTypeName()
    {
        return t('Hosting Project list');
    }

    public function view()
    {

        $client = new Client();
        $response = $client->get('http://api.concretecms.com.test/api/projects');
        print $response->getBody();
    }
}
