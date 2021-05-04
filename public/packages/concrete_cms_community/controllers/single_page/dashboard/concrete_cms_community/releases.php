<?php

namespace Concrete\Package\ConcreteCmsCommunity\Controller\SinglePage\Dashboard\ConcreteCmsCommunity;

use Concrete\Core\Form\Service\Widget\DateTime;
use Concrete\Core\Navigation\Breadcrumb\Dashboard\DashboardBreadcrumbFactory;
use Concrete\Core\Navigation\Item\Item;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Utility\Service\Validation\Strings;
use Concrete\Core\Validation\SanitizeService;
use PortlandLabs\Hosting\Api\Client\Client;
use PortlandLabs\Hosting\Api\Client\Query\Resource\GetConcreteReleasesQuery;
use PortlandLabs\Hosting\Software\Command\CreateConcreteReleaseCommand;
use PortlandLabs\Hosting\Software\Command\DeleteConcreteReleaseCommand;
use PortlandLabs\Hosting\Software\Command\UpdateConcreteReleaseCommand;
use PortlandLabs\Hosting\Software\ConcreteRelease;
use PortlandLabs\Hosting\Software\ConcreteReleaseRepository;

class Releases extends DashboardPageController
{

    public function view()
    {
        $query = new GetConcreteReleasesQuery();
        $client = $this->app->make(Client::class);
        $result = $client->getCollection($query);
        $this->set('releases', $result->getMembers());
    }

    public function view_details($releaseId = null)
    {
        $release = $this->getRelease($releaseId);
        $this->setCurrentRelease($release);
        $this->render('/dashboard/concrete_cms_community/releases/details');
    }

    public function getRelease($releaseId)
    {
        $release = null;
        if ($releaseId) {
            $release = $this->app->make(ConcreteReleaseRepository::class)
                ->findOneById($releaseId);
        }

        if (!$release) {
            $this->flash('error', t('Invalid release ID'));
            return $this->buildRedirect(['/dashboard/concrete_cms_community/releases']);
        }

        return $release;
    }

    protected function setCurrentRelease(ConcreteRelease $release)
    {
        $this->set('release', $release);
        $breadcrumb = $this->app->make(DashboardBreadcrumbFactory::class)->getBreadcrumb($this->getPageObject());
        $breadcrumb->add(new Item('', $release->getVersion()));
        $this->setBreadcrumb($breadcrumb);
    }

    public function delete_release()
    {
        $releaseId = (int) $this->request->request->get('id');
        $release = $this->app->make(ConcreteReleaseRepository::class)
            ->findOneById($releaseId);
        if ($release === null) {
            $this->flash('error', t('The release specified does not exist.'));
            return $this->buildRedirect($this->action());
        }
        if (!$this->token->validate('delete_release')) {
            $this->error->add($this->token->getErrorMessage());
        }
        if (!$this->error->has()) {
            $command = new DeleteConcreteReleaseCommand($releaseId);
            $this->executeCommand($command);
            $this->flash('success', t('Release removed successfully.'));

            return $this->buildRedirect($this->action());
        }
        return $this->view_details($release->getId());
    }

    public function add_release()
    {
        $this->render('/dashboard/concrete_cms_community/releases/form');
    }

    protected function validateReleaseRequest()
    {
        $security = new SanitizeService();
        $strings = $this->app->make(Strings::class);
        $version = $security->sanitizeString($this->post('version'));

        if (!$strings->notempty($version)) {
            $this->error->add(t('You must specify a valid version for your project.'));
        }

        return [$version];
    }

    public function create_release()
    {
        list($version) = $this->validateReleaseRequest();
        if (!$this->token->validate('create_release')) {
            $this->error->add(t($this->token->getErrorMessage()));
        }
        if (!$this->error->has()) {
            $datePicker = new DateTime();
            $command = new CreateConcreteReleaseCommand();
            $command->setVersion($version);
            $command->setDateReleased($datePicker->translate('dateReleased'));
            $command->setDownloadUrl($this->request->request->get('downloadUrl'));
            $command->setReleaseNotes($this->request->request->get('releaseNotes'));
            $command->setUpgradeNotes($this->request->request->get('upgradeNotes'));
            $command->setReleaseNotesUrl($this->request->request->get('releaseNotesUrl'));
            $command->setIsPublished($this->request->request->get('isPublished') ? true : false);
            $command->setMd5sum($this->request->request->get('md5sum'));
            $release = $this->executeCommand($command);
            $this->flash('success', t('Release created successfully.'));

            return $this->buildRedirect(['/dashboard/concrete_cms_community/releases', 'view_details', $release->getId()]);
        }
        $this->view();
    }

    public function edit($releaseId = null)
    {
        $release = $this->getRelease($releaseId);
        $this->setCurrentRelease($release);
        $this->render('/dashboard/concrete_cms_community/releases/form');
    }

    public function update_release($releaseId = null)
    {
        $release = $this->getRelease($releaseId);
        list($version) = $this->validateReleaseRequest();
        $this->setCurrentRelease($release);
        if (!$this->token->validate('update_release')) {
            $this->error->add(t($this->token->getErrorMessage()));
        }
        if (!$this->error->has()) {
            $datePicker = new DateTime();
            $command = new UpdateConcreteReleaseCommand($release->getId());
            $command->setVersion($version);
            $command->setDateReleased($datePicker->translate('dateReleased'));
            $command->setDownloadUrl($this->request->request->get('downloadUrl'));
            $command->setReleaseNotes($this->request->request->get('releaseNotes'));
            $command->setUpgradeNotes($this->request->request->get('upgradeNotes'));
            $command->setReleaseNotesUrl($this->request->request->get('releaseNotesUrl'));
            $command->setIsPublished($this->request->request->get('isPublished') ? true : false);
            $command->setMd5sum($this->request->request->get('md5sum'));

            $release = $this->executeCommand($command);
            $this->flash('success', t('Release updated successfully.'));

            return $this->buildRedirect(['/dashboard/concrete_cms_community/releases', 'view_details', $release->getId()]);
        }
        $this->render('/dashboard/concrete_cms_community/releases/form');
    }
}
