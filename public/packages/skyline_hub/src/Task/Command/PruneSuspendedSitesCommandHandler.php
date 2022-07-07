<?php

namespace PortlandLabs\Skyline\Task\Command;

use Concrete\Core\Application\Application;
use Concrete\Core\Logging\Handler\DatabaseHandler;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Command\DeleteHostingSiteCommand;
use PortlandLabs\Skyline\Entity\Site;

defined('C5_EXECUTE') or die("Access Denied.");

class PruneSuspendedSitesCommandHandler
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Application
     */
    protected $app;

    public function __construct(EntityManager $entityManager, Application $app)
    {
        $this->entityManager = $entityManager;
        $this->app = $app;
    }


    public function __invoke(PruneSuspendedSitesCommand $command)
    {
        $current = new \DateTime();
        $back = $current->sub(
            new \DateInterval(
                'P' . $_ENV['SKYLINE_DAYS_AFTER_SUSPENDING_TO_KEEP_SITE'] . 'D'
            )
        );
        $threshold = $back->getTimestamp();
        $qb = $this->entityManager->getRepository(Site::class)->createQueryBuilder('s');
        $sites = $qb->select('s')
            ->where($qb->expr()->lt('s.suspendedTimestamp', ':threshold'))
            // Note: Admin suspensions are NOT included in this list, intentionally.
            ->andWhere($qb->expr()->in('s.status', [Site::STATUS_SUSPENDED_TRIAL_CANCELLED, Site::STATUS_SUSPENDED_UNPAID]))
            ->getQuery()->execute([':threshold' => $threshold]);

        foreach ($sites as $site) {
            $command = new DeleteHostingSiteCommand($site->getId());
            $this->app->executeCommand($command);
        }

    }


}
