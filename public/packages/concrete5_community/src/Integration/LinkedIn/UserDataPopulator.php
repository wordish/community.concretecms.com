<?php

namespace Concrete5\Community\Integration\Linkedin;

use Concrete\Core\User\UserInfo;
use OAuth\OAuth2\Service\Linkedin;
use OAuth\OAuth2\Token\TokenInterface;
use Throwable;

class UserDataPopulator
{

    /**
     * @var \OAuth\OAuth2\Service\Linkedin
     */
    private $service;

    public function __construct(Linkedin $service)
    {
        $this->service = $service;
    }

    /**
     * Populate user attributes using stackoverflow's API
     *
     * @param \Concrete\Core\User\UserInfo $user
     * @param \OAuth\OAuth2\Token\TokenInterface $token
     *
     * @return bool
     */
    public function populate(UserInfo $user, TokenInterface $token): bool
    {
        // Make sure we actually have an access token
        if (!$token->getAccessToken()) {
            return false;
        }



        $userData = $this->fetch('/me?projection=(id,educations,vanityName)');
        if ($userData) {
            dd($userData);

            // Try to resolve all the data we need from the response
            $userId = $userData['id'] ?? null;

            $education = $this->fetch('/me?projection=(id,educations)');
            dd($education);
        }

        // If we don't even have a user ID we don't have anything usable
        if (!$userId) {
            return false;
        }

        // Store information against the user attributes
        $user->setAttribute('stackoverflow_badges_bronze', $bronze);
        $user->setAttribute('stackoverflow_badges_silver', $silver);
        $user->setAttribute('stackoverflow_badges_gold', $gold);
        $user->setAttribute('stackoverflow_badges', $bronze + $silver + $gold);
        $user->setAttribute('stackoverflow_user_id', $userId);
        $user->setAttribute('stackoverflow_reputation', $reputation);

        return true;
    }

    public function fetch($path, $method = 'GET', $body = null, array $extraHeaders = []): ?array
    {
        // Try to fetch the user info
        try {
            $result = $this->service->request($path, $method, $body, $extraHeaders);
            if ($result) {
                return json_decode($result, true);
            }
        } catch (Throwable $e) {
            // Ignore errors
        }

        return null;
    }

}
