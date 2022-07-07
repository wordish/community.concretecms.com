<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Account\Hosting;

use Concrete\Core\Page\Controller\AccountPageController;
use Concrete\Core\Utility\Service\Number;
use PortlandLabs\Skyline\Command\CancelHostingTrialSiteCommand;
use PortlandLabs\Skyline\Controller\Traits\RetrieveAndValidateSiteTrait;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Stripe\StripeService;

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
        $hostingSite = $this->retrieveAndValidateSiteForViewing($uuid);
        $this->set('numberHelper', new Number());
        $this->set('hostingSite', $hostingSite);
        if ($hostingSite->getStatus() == Site::STATUS_INSTALLING) {
            $this->render('/account/hosting/project/install');
        } else {
            $invoices = $this->app->make(StripeService::class)->getOpenInvoices($hostingSite);
            $this->set('invoices', $invoices);
            $this->render('/account/hosting/project/details');
        }
    }

    public function cancel_trial($uuid = null)
    {
        $hostingSite = $this->retrieveAndValidateSiteForViewing($uuid, $this->get('profile')->getUserObject());
        if ($hostingSite->getSubscriptionStatus() == Site::SUBSCRIPTION_STATUS_TRIALING) {
            if (!$this->get('token')->validate('cancel_trial')) {
                throw new \Exception($this->get('token')->getErrorMessage());
            } else {
                $command = new CancelHostingTrialSiteCommand($hostingSite->getId());
                $this->executeCommand($command);
                $this->flash('success', t('Trial cancelled.'));
                return $this->buildRedirect(['/account/hosting/']);
            }
        } else {
            throw new \Exception(t('This site is not a trial.'));
        }
    }
}