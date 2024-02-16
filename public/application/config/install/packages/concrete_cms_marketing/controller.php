<?php

namespace Application\StartingPointPackage\ConcreteCmsMarketing;

use Concrete\Core\Package\StartingPointPackage;

class Controller extends StartingPointPackage
{
    protected $pkgHandle = 'concrete_cms_marketing';

    public function getPackageName()
    {
        return t('concretecms.com');
    }

    public function getPackageDescription()
    {
        return 'Installs the concretecms.com starting point.';
    }

}
