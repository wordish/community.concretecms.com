<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Dashboard\Skyline\Sites;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Permission\Checker;
use Concrete\Core\Routing\Redirect;
use PortlandLabs\Skyline\Command\CreateHostingSiteBackupCommand;
use PortlandLabs\Skyline\Command\DeleteHostingSiteBackupCommand;
use PortlandLabs\Skyline\Command\DeleteHostingSiteCommand;
use PortlandLabs\Skyline\Command\ReinstateHostingSiteCommand;
use PortlandLabs\Skyline\Command\SuspendHostingSiteCommand;
use PortlandLabs\Skyline\Controller\Traits\RetrieveAndValidateSiteTrait;
use PortlandLabs\Skyline\Entity\Backup;
use Aws\S3\S3Client;

class Details extends DashboardPageController
{

    use RetrieveAndValidateSiteTrait;

    public function view($id = null)
    {
        $this->requireAsset('skyline/frontend');
        $hostingSite = $this->retrieveAndValidateSiteForViewing($id);
        $this->set('hostingSite', $hostingSite);
        $allowSuspend = false;
        $allowReinstate = false;
        $checker = new Checker();
        if ($checker->canUpdateSkylineSiteEntries()) {
            if ($hostingSite->isSuspended()) {
                $allowReinstate = true;
            } else {
                $allowSuspend = true;
            }
        }
        $this->set('allowSuspend', $allowSuspend);
        $this->set('allowReinstate', $allowReinstate);
        $this->set('allowDelete', (new Checker())->canDeleteSkylineSiteEntries());
        $this->set('backURL', \URL::to('/dashboard/skyline/sites'));
        $this->set('editURL', \URL::to('/dashboard/skyline/sites/edit', $hostingSite->getID()));
    }

    public function delete($id = null)
    {
        $hostingSite = $this->retrieveAndValidateSiteForDeleting($id);
        if (!$this->token->validate("delete")) {
            $this->error->add($this->token->getErrorMessage());
        }

        if (!$this->error->has()) {
            $command = new DeleteHostingSiteCommand($hostingSite->getId());
            $this->executeCommand($command);
            $this->flash('success', t('Site removed successfully.'));
            return $this->buildRedirect(['/dashboard/skyline/sites']);
        }
        $this->view($id);
    }

    public function suspend($id = null)
    {
        $hostingSite = $this->retrieveAndValidateSiteForEditing($id);
        if (!$this->token->validate("suspend")) {
            $this->error->add($this->token->getErrorMessage());
        }
        if ($hostingSite->isSuspended()) {
            $this->error->add(t('This site is already suspended.'));
        }
        if (!$this->error->has()) {
            $command = new SuspendHostingSiteCommand($hostingSite->getId());
            $this->executeCommand($command);
            $this->flash('success', t('Site suspended successfully.'));
            return $this->buildRedirect(['/dashboard/skyline/sites']);
        }
        $this->view($id);
    }

    public function reinstate($id = null)
    {
        $hostingSite = $this->retrieveAndValidateSiteForEditing($id);
        if (!$this->token->validate("reinstate")) {
            $this->error->add($this->token->getErrorMessage());
        }
        if (!$hostingSite->isSuspended()) {
            $this->error->add(t('This site is not suspended.'));
        }
        if (!$this->error->has()) {
            $command = new ReinstateHostingSiteCommand($hostingSite->getId());
            $this->executeCommand($command);
            $this->flash('success', t('Site reinstatement requested.'));
            return $this->buildRedirect(['/dashboard/skyline/sites']);
        }
        $this->view($id);
    }

    public function download_backup($backupId = null)
    {
        $backup = $this->entityManager->find(Backup::class, $backupId);
        if (!$backup) {
            throw new \Exception(t('Invalid backup ID.'));
        }
        $site = $backup->getSite();
        $this->retrieveAndValidateSiteForViewing($site);

        $client = $this->app->make(S3Client::class);
        $cmd = $client->GetCommand('GetObject', [
            'Bucket' => $_ENV['AWS_BUCKET_BACKUPS'],
            'Key' => $backup->getBackupFileID(),
        ]);
        $request = $client->createPresignedRequest($cmd, '+20 minutes');
        return Redirect::to((string) $request->getUri());
    }

    public function delete_backup($backupId = null)
    {
        $backup = $this->entityManager->find(Backup::class, $backupId);
        if (!$backup) {
            throw new \Exception(t('Invalid backup ID.'));
        }
        $site = $backup->getSite();
        $this->retrieveAndValidateSiteForEditing($site);

        $command = new DeleteHostingSiteBackupCommand($backup->getId());
        $this->executeCommand($command);
        $this->flash('success', t('Backup deleted successfully.'));
        return $this->buildRedirect(['/dashboard/skyline/sites', 'details', $site->getID()]);
    }

    public function create_backup($id = null)
    {
        $site = $this->retrieveAndValidateSiteForViewing($id);
        $command = new CreateHostingSiteBackupCommand($site->getId());
        $this->executeCommand($command);
        $this->flash('success', t('Backup started...'));
        return $this->buildRedirect(['/dashboard/skyline/sites', 'details', $site->getID()]);
    }



}
