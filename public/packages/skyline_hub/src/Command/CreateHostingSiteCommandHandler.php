<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\User\UserInfoRepository;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\CreateSiteInNeighborhoodCommand;
use PortlandLabs\Skyline\Site\SiteHandleGenerator;
use PortlandLabs\Skyline\Stripe\StripeService;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateHostingSiteCommandHandler
{

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @var UserInfoRepository
     */
    protected $userInfoRepository;

    /**
     * @var SiteHandleGenerator
     */
    protected $siteHandleGenerator;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    /**
     * CreateHostingSiteCommandHandler constructor.
     * @param StripeService $stripeService
     * @param UserInfoRepository $userInfoRepository
     * @param SiteHandleGenerator $siteHandleGenerator
     * @param EntityManager $entityManager
     * @param MessageBusInterface $messageBus
     */
    public function __construct(
        StripeService $stripeService,
        UserInfoRepository $userInfoRepository,
        SiteHandleGenerator $siteHandleGenerator,
        EntityManager $entityManager,
        MessageBusInterface $messageBus
    ) {
        $this->stripeService = $stripeService;
        $this->userInfoRepository = $userInfoRepository;
        $this->siteHandleGenerator = $siteHandleGenerator;
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateHostingSiteCommand $command)
    {
        $userinfo = $this->userInfoRepository->getByID($command->getAuthor());
        if ($command->useTestClock()) {
            $testClock = $this->stripeService->createTestClock(time());
            $customer = $this->stripeService->createTestCustomer($userinfo->getUserEmail(), $testClock);
        } else {
            $customer = $this->stripeService->getCustomer($userinfo);
        }
        $price = $this->stripeService->getProductPrice($_ENV['SKYLINE_DEFAULT_PRODUCT_PRICE_ID']);
        $subscription = $this->stripeService->createSubscription($customer, $price);

        $siteName = $command->getSiteName();
        $neighborhood = $command->getNeighborhood();
        $siteHandle = $this->siteHandleGenerator->createSiteHandle($command);
        $generatedAdminPassword = bin2hex(random_bytes(8));
        $author = $userinfo->getEntityObject();

        $hostingEntry = new Site();
        $hostingEntry->setNeighborhood($neighborhood);
        $hostingEntry->setName($siteName);
        $hostingEntry->setHandle($siteHandle);
        $hostingEntry->setAuthor($author);
        $hostingEntry->setAdminPassword($generatedAdminPassword);
        $hostingEntry->setStatus(Site::STATUS_INSTALLING);
        $hostingEntry->setSubscriptionStatus($subscription->status);
        $hostingEntry->setSubscriptionId($subscription->id);

        $this->entityManager->persist($hostingEntry);
        $this->entityManager->flush();

        if ($command->provisionAccount()) {
            $command = new CreateSiteInNeighborhoodCommand();
            $command->setNeighborhood($neighborhood);
            $command->setEmail($author->getUserEmail());
            $command->setSiteHandle($siteHandle);
            $command->setConcreteAdminPassword($generatedAdminPassword);

            $this->messageBus->dispatch($command);
        }

        return $hostingEntry;


    }

    
}
