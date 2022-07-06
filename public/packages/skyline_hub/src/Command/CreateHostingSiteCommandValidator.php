<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Form\Service\Widget\UserSelector;
use Concrete\Core\Foundation\Command\Command;
use Concrete\Core\Foundation\Command\ValidatorInterface;
use Concrete\Core\User\UserInfoRepository;
use Concrete\Core\Utility\Service\Validation\Strings;
use PortlandLabs\Skyline\NeighborhoodList;

class CreateHostingSiteCommandValidator implements ValidatorInterface
{

    /**
     * @var Strings
     */
    protected $strings;

    /**
     * @var UserSelector
     */
    protected $userSelector;

    /**
     * @var UserInfoRepository
     */
    protected $userInfoRepository;

    /**
     * @var NeighborhoodList
     */
    protected $neighborhoodList;

    public function __construct(NeighborhoodList $neighborhoodList, UserInfoRepository $userInfoRepository, UserSelector $userSelector, Strings $strings)
    {
        $this->neighborhoodList = $neighborhoodList;
        $this->userInfoRepository = $userInfoRepository;
        $this->strings = $strings;
        $this->userSelector = $userSelector;
    }

    /**
     * @param CreateHostingSiteCommand $command
     * @return \Concrete\Core\Error\ErrorList\ErrorList|void
     */
    public function validate($command)
    {
        $error = app('error');

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

        $neighborhood = $this->neighborhoodList->getByHandle($command->getNeighborhood());
        if (!$neighborhood) {
            $error->add(t('You must choose a valid neighborhood (hosting server) for this account.'));
        }

        return $error;
    }


}
