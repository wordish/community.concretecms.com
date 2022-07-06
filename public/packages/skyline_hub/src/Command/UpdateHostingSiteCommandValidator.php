<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Foundation\Command\ValidatorInterface;
use Concrete\Core\User\UserInfoRepository;
use Concrete\Core\Utility\Service\Validation\Strings;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\NeighborhoodList;
use Stripe\Stripe;
use Stripe\StripeClient;

class UpdateHostingSiteCommandValidator implements ValidatorInterface
{

    /**
     * @var Strings
     */
    protected $strings;

    /**
     * @var UserInfoRepository
     */
    protected $userInfoRepository;

    /**
     * @var StripeClient
     */
    protected $stripe;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager, StripeClient $stripe, NeighborhoodList $neighborhoodList, UserInfoRepository $userInfoRepository, Strings $strings)
    {
        $this->entityManager = $entityManager;
        $this->stripe = $stripe;
        $this->userInfoRepository = $userInfoRepository;
        $this->strings = $strings;
    }

    /**
     * @param UpdateHostingSiteCommand $command
     * @return \Concrete\Core\Error\ErrorList\ErrorList|void
     */
    public function validate($command)
    {
        $error = app('error');

        $hostingSite = $this->entityManager->find(Site::class, $command->getId());
        if (!$hostingSite) {
            $error->add(t('Invalid hosting site ID.'));
        }

        $author = null;
        $user = $this->userInfoRepository->getByID($command->getAuthor());
        if ($user) {
            $author = $user->getEntityObject();
        }
        if (!$author) {
            $error->add(t('You must attach this hosting account to a valid registered user.'));
        }

        /**
         * @var $validator Strings
         */
        if (!$this->strings->min((string) $command->getSiteName(), 4)) {
            $error->add(t("Your site name must be a minimum of 4 characters long."));
        }

        if ($command->getSubscriptionId()) {
            try {
                $subscription = $this->stripe->subscriptions->retrieve($command->getSubscriptionId());
            } catch (\Exception $e) {
                $error->add(t('Invalid subscription ID.'));
            }
        }

        return $error;
    }


}
