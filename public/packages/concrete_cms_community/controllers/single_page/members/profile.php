<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace Concrete\Package\ConcreteCmsCommunity\Controller\SinglePage\Members;

use Concrete\Controller\SinglePage\Members\Profile as CoreProfile;

class Profile extends CoreProfile
{
    public function on_start()
    {
        $this->set('exclude_breadcrumb', true);

        return parent::on_start();
    }

}