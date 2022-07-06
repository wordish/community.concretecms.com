<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Dashboard\Skyline\Sites;

use Concrete\Core\Form\Service\Widget\UserSelector;
use Concrete\Core\Page\Controller\DashboardPageController;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommand;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommandValidator;
use PortlandLabs\Skyline\Command\UpdateHostingSiteCommand;
use PortlandLabs\Skyline\Command\UpdateHostingSiteCommandValidator;
use PortlandLabs\Skyline\Controller\Traits\RetrieveAndValidateSiteTrait;

class Edit extends DashboardPageController
{

    use RetrieveAndValidateSiteTrait;

    public function view($id = null)
    {
        $this->requireAsset('skyline/frontend');
        $hostingSite = $this->retrieveAndValidateSiteForEditing($id);
        $this->set('hostingSite', $hostingSite);
        $this->set('backURL', \URL::to('/dashboard/skyline/sites', 'details', $hostingSite->getId()));
        $this->set('userSelector', $this->app->make(UserSelector::class));
    }

    public function submit($id = null)
    {
        $hostingSite = $this->retrieveAndValidateSiteForEditing($id);
        $command = new UpdateHostingSiteCommand($hostingSite->getId());
        $command->setAuthor((int) $this->request->request->get('author'));
        $command->setSiteName($this->request->request->get('name'));
        $command->setSubscriptionId($this->request->request->get('subscriptionId'));

        if (!$this->token->validate('submit')) {
            $this->error->add($this->token->getErrorMessage());
        }

        $error = $this->app->make(UpdateHostingSiteCommandValidator::class)->validate($command);
        if ($error->has()) {
            $this->error->add($error);
        }

        if (!$this->error->has()) {
            $site = $this->app->executeCommand($command);
            $this->flash('success', t('Site updated successfully.'));
            return $this->buildRedirect(['/dashboard/skyline/sites/details', $site->getId()]);
        }
        $this->view($id);
    }





}
