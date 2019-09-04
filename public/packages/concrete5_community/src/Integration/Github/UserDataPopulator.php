<?php

namespace Concrete5\Community\Integration\Github;

use Concrete\Core\User\UserInfo;
use OAuth\OAuth2\Service\GitHub;
use OAuth\OAuth2\Token\TokenInterface;

class UserDataPopulator
{

    /**
     * @var \OAuth\OAuth2\Service\GitHub
     */
    private $service;

    /**
     * @var \Concrete5\Community\Integration\Github\GraphqlClient
     */
    private $client;

    public function __construct(GitHub $service, GraphqlClient $client)
    {
        $this->service = $service;
        $this->client = $client;
    }

    /**
     * Populate user attributes
     * Since we're currently populating github participation in realtime we don't really need to populate anything here
     * yet.
     *
     * @param \Concrete\Core\User\UserInfo $user
     * @param \OAuth\OAuth2\Token\TokenInterface $token
     *
     * @return bool
     *
     * @todo Make this actually populate something.
     *
     */
    public function populate(UserInfo $user, TokenInterface $token): bool
    {
        return true;
    }
}
