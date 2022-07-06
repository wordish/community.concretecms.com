<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Dashboard\Skyline\Sites;

use Concrete\Core\Form\Service\Widget\UserSelector;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\User\UserInfoRepository;
use Concrete\Core\Utility\Service\Validation\Strings;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommand;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommandValidator;
use PortlandLabs\Skyline\NeighborhoodList;
use PortlandLabs\Skyline\NeighborhoodListFactory;

class Add extends DashboardPageController
{

    /**
     * @var NeighborhoodList
     */
    protected $neighborhoodList;

    public function on_start()
    {
        $this->neighborhoodList = $this->app->make(NeighborhoodListFactory::class)->createList();
        parent::on_start();
    }

    public function view()
    {
        $this->set('neighborhoodList', $this->neighborhoodList);
        $this->set('userSelector', $this->app->make(UserSelector::class));
    }

    public function submit()
    {

        $command = new CreateHostingSiteCommand();
        $command->setAuthor((int) $this->request->request->get('author'));
        $command->setSiteName($this->request->request->get('name'));
        $command->setNeighborhood($this->request->request->get('neighborhood'));

        if ($this->request->request->get('provisionAccount')) {
            $command->setProvisionAccount(true);
        } else {
            $command->setProvisionAccount(false);
        }

        if (!$this->token->validate('submit')) {
            $this->error->add($this->token->getErrorMessage());
        }

        $error = $this->app->make(CreateHostingSiteCommandValidator::class)->validate($command);
        if ($error->has()) {
            $this->error->add($error);
        }

        if (!$this->error->has()) {
            if ($_ENV['SKYLINE_ENABLE_TESTING_TOOLS']) {
                if ($this->request->request->get('attachToTestClock')) {
                    $command->setAttachToTestClock(true);
                }
            }
            $site = $this->app->executeCommand($command);
            return $this->buildRedirect(['/dashboard/skyline/sites/details', $site->getId()]);
        }
        $this->view();
    }
}
