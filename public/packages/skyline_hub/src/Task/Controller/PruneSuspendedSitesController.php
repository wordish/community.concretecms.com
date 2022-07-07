<?php
namespace PortlandLabs\Skyline\Task\Controller;

use Concrete\Core\Command\Task\Controller\AbstractController;
use Concrete\Core\Command\Task\Input\InputInterface;
use Concrete\Core\Command\Task\Runner\CommandTaskRunner;
use Concrete\Core\Command\Task\Runner\TaskRunnerInterface;
use Concrete\Core\Command\Task\TaskInterface;
use PortlandLabs\Skyline\Task\Command\PruneSuspendedSitesCommand;

defined('C5_EXECUTE') or die("Access Denied.");

class PruneSuspendedSitesController extends AbstractController
{

    public function getName(): string
    {
        return t('Prune Suspended Sites');
    }

    public function getDescription(): string
    {
        return t('Checks for all sites suspended further in the past than the threshold and removes them.');
    }

    public function getTaskRunner(TaskInterface $task, InputInterface $input): TaskRunnerInterface
    {
        return new CommandTaskRunner($task, new PruneSuspendedSitesCommand(), t('Prune messages sent to queue.'));
    }


}
