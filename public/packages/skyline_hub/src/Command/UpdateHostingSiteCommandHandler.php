<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\User\UserInfoRepository;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;

class UpdateHostingSiteCommandHandler
{

    /**
     * @var UserInfoRepository
     */
    protected $userInfoRepository;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(
        UserInfoRepository $userInfoRepository,
        EntityManager $entityManager
    ) {
        $this->userInfoRepository = $userInfoRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(UpdateHostingSiteCommand $command)
    {
        $userinfo = $this->userInfoRepository->getByID($command->getAuthor());
        $author = $userinfo->getEntityObject();

        $siteName = $command->getSiteName();

        $hostingEntry = $this->entityManager->find(Site::class, $command->getId());
        $hostingEntry->setName($siteName);
        $hostingEntry->setAuthor($author);
        $hostingEntry->setSubscriptionId($command->getSubscriptionId());

        $this->entityManager->persist($hostingEntry);
        $this->entityManager->flush();

        return $hostingEntry;
    }


}
