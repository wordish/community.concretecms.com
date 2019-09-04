<?php

namespace Concrete5\Community\Integration\Stackoverflow;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\User\UserInfo;
use OAuth\OAuth2\Token\TokenInterface;
use Throwable;

class UserDataPopulator
{

    /**
     * @var \Concrete\Core\Config\Repository\Repository
     */
    private $config;

    /**
     * @var \Concrete5\Community\Integration\Stackoverflow\StackoverflowService
     */
    private $service;

    public function __construct(Repository $config, StackoverflowService $service)
    {
        $this->config = $config;
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
        $key = $this->config->get('concrete5_community::integrations.stackoverflow.key');

        // Make sure we actually have an access token
        if (!$token->getAccessToken()) {
            return false;
        }

        // Try to fetch the user info
        try {
            $result = $this->service->request('/me?site=stackoverflow&key=' . urlencode($key));
            if ($result && $result[0] !== '{') {
                // For some reason I get gzipped responses on my local machine. This may not be necessary in production
                $result = gzdecode($result);
            }
        } catch (Throwable $e) {
            return false;
        }

        // Try to resolve all the data we need from the response
        $userData = array_get((array) json_decode($result, true), 'items.0', []);
        $userId = $userData['user_id'] ?? null;
        $bronze = $userData['badge_counts']['bronze'] ?? 0;
        $silver = $userData['badge_counts']['silver'] ?? 0;
        $gold = $userData['badge_counts']['gold'] ?? 0;
        $reputation = $userData['reputation'] ?? 0;

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

}
