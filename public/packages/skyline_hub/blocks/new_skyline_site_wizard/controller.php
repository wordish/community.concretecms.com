<?php

namespace Concrete\Package\SkylineHub\Block\NewSkylineSiteWizard;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Html\Service\Navigation;
use Concrete\Core\Routing\Redirect;
use Concrete\Core\User\User;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommand;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommandValidator;
use PortlandLabs\Skyline\NeighborhoodList;
use PortlandLabs\Skyline\NeighborhoodListFactory;
use PortlandLabs\Skyline\NeighborhoodSelector;
use PortlandLabs\Skyline\Stripe\StripeService;

class Controller extends BlockController
{
    public function getBlockTypeDescription()
    {
        return t('Creates a wizard interface to select a new Skyline Hosting project.');
    }

    public function getBlockTypeName()
    {
        return t('Skyline Wizard');
    }

    public function view()
    {
        $this->set('token', $this->app->make('token'));
        $this->set('u', $this->app->make(User::class));
        $this->set('navigation', $this->app->make(Navigation::class));
    }

    public function action_create_site()
    {
        $token = $this->app->make('token');
        $error = $this->app->make('error');
        if (!$token->validate('create_site')) {
            $error->add($token->getErrorMessage());
        }
        $command = new CreateHostingSiteCommand();
        $command->setNeighborhood($this->app->make(NeighborhoodSelector::class)->chooseNeighborhoodForNewSite()->getHandle());
        $command->setSiteName($this->request->request->get('hosting_site_name'));
        $command->setAuthor($this->app->make(User::class)->getUserID());
        $error->add($this->app->make(CreateHostingSiteCommandValidator::class)->validate($command));

        if (!$error->has()) {
            $hostingEntry = $this->app->executeCommand($command);
            return Redirect::to('/account/hosting/project', $hostingEntry->getID());
        }
        $this->set('error', $error);
        $this->view();
    }
}
