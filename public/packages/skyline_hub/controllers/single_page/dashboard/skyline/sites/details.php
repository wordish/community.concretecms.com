<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Dashboard\Skyline\Sites;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Permission\Checker;
use PortlandLabs\Skyline\Command\DeleteHostingSiteCommand;
use PortlandLabs\Skyline\Controller\Traits\RetrieveAndValidateSiteTrait;

class Details extends DashboardPageController
{

    use RetrieveAndValidateSiteTrait;

    public function view($id = null)
    {
        $this->requireAsset('skyline/frontend');
        $hostingSite = $this->retrieveAndValidateSite($id);
        $this->set('hostingSite', $hostingSite);
        $this->set('allowDelete', (new Checker())->canDeleteSkylineSiteEntries());
        $this->set('backURL', \URL::to('/dashboard/skyline/sites'));
        $this->set('editURL', \URL::to('/dashboard/skyline/sites', 'edit', $hostingSite->getID()));
    }

    public function delete($id = null)
    {
        $hostingSite = $this->retrieveAndValidateSite($id);
        if (!$this->token->validate("delete")) {
            $this->error->add($this->token->getErrorMessage());
        }

        $checker = new Checker();
        if (!$checker->canDeleteSkylineSiteEntries()) {
            $this->error->add(t('You do not have access to remove this site entry.'));
        }

        if (!$this->error->has()) {
            $command = new DeleteHostingSiteCommand($hostingSite->getId());
            $this->executeCommand($command);
            $this->flash('success', t('Site removed successfully.'));
            return $this->buildRedirect(['/dashboard/skyline/sites']);
        }
        $this->view($id);
    }
}
