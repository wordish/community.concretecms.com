<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Account\Hosting;

use Concrete\Core\Page\Controller\AccountPageController;
use Concrete\Core\Utility\Service\Number;
use PortlandLabs\Skyline\Command\TerminateHostingTrialSiteCommand;
use PortlandLabs\Skyline\Controller\Traits\RetrieveAndValidateSiteTrait;
use PortlandLabs\Skyline\Entity\Site;

class Project extends AccountPageController
{

    use RetrieveAndValidateSiteTrait;

    public function on_start()
    {
        parent::on_start();
        $this->requireAsset('skyline/frontend');
        $this->set('token', $this->app->make('token'));
    }

    public function view($uuid = null)
    {
        $hostingSite = $this->retrieveAndValidateSite($uuid, $this->get('profile')->getUserObject());
        $this->set('numberHelper', new Number());
        $this->set('hostingSite', $hostingSite);
        if ($hostingSite->getStatus() == Site::STATUS_INSTALLING) {
            $this->render('/account/hosting/project/install');
        } else {
            $this->render('/account/hosting/project/details');
        }
    }

    public function cancel_trial($uuid = null)
    {
        $hostingSite = $this->retrieveAndValidateSite($uuid, $this->get('profile')->getUserObject());
        if ($hostingSite->getSubscriptionStatus() == Site::SUBSCRIPTION_STATUS_TRIALING) {
            if (!$this->get('token')->validate('cancel_trial')) {
                throw new \Exception($this->get('token')->getErrorMessage());
            } else {
                $command = new TerminateHostingTrialSiteCommand($hostingSite->getId());
                $this->executeCommand($command);
                $this->flash('success', t('Trial cancelled.'));
                return $this->buildRedirect(['/account/hosting/']);
            }
        } else {
            throw new \Exception(t('This site is not a trial.'));
        }
    }
}