<?php

namespace Concrete\Package\Concrete5Community\Controller\SinglePage\Account;

use Concrete\Core\Database\DatabaseManager;
use Concrete\Core\Http\ResponseFactoryInterface;
use Concrete\Core\Page\Controller\AccountPageController;
use Concrete\Core\Url\Resolver\Manager\ResolverManager;
use Concrete\Core\User\User;
use Concrete5\Community\Integration\Linkedin\UserDataPopulator;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Service\Linkedin as LinkedinService;
use OAuth\OAuth2\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;

class LinkedIn extends AccountPageController
{

    /**
     * @var \Concrete\Core\Http\ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var \Concrete5\Community\Integration\Linkedin\UserDataPopulator
     */
    private $populator;

    /**
     * @var \Concrete\Core\Database\DatabaseManager
     */
    private $database;

    /**
     * @var \OAuth\OAuth2\Service\Linkedin
     */
    private $service;

    /**
     * @var \Concrete\Core\Validation\CSRF\Token
     */
    protected $token;

    /**
     * @var \Concrete\Core\Url\Resolver\Manager\ResolverManager
     */
    private $resolverManager;

    public function __construct(
        $c,
        ResponseFactoryInterface $responseFactory,
        UserDataPopulator $populator,
        DatabaseManager $database,
        LinkedinService $linkedin,
        ResolverManager $resolverManager
    ) {
        parent::__construct($c);
        $this->responseFactory = $responseFactory;
        $this->populator = $populator;
        $this->database = $database;
        $this->service = $linkedin;
        $this->resolverManager = $resolverManager;
    }

    /**
     * Handle setting data for basic display
     */
    public function view(): void
    {
    }

    /**
     * Start the oauth2 authorization flow
     *
     * @param string|null $token
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function connect(string $token = null): Response
    {
        if ($this->token->validate('linkedin_connect', $token)) {
            $url = $this->service->getAuthorizationUri([]);
            return $this->responseFactory->redirect($url, Response::HTTP_TEMPORARY_REDIRECT);
        }

        return $this->handleError($this->token->getErrorMessage());
    }

    /**
     * Attempt to store the token
     * This method uses our populator to attempt to get data about the user.
     * If the populator isn't able to resolve a valid user, we don't store anything.
     *
     * @param \OAuth\OAuth2\Token\TokenInterface $token
     *
     * @return bool
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    protected function storeToken(TokenInterface $token): bool
    {
        $user = $this->app->make(User::class)->getUserInfoObject();
        if ($user && $this->populator->populate($user, $token)) {
            $db = $this->database->connection();

            // Clear out old binding
            $db->delete('OauthUserMap', [
                'user_id' => $user->getUserID(),
                'namespace' => 'integration_linkedin',
            ]);

            // Make new binding
            $db->insert('OauthUserMap', [
                'user_id' => $user->getUserID(),
                'binding' => $token->getAccessToken(),
                'namespace' => 'integration_linkedin',
            ]);

            return true;
        }

        return false;
    }

    /**
     * Handle the second half of the oauth flow, after the user authorizes us
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function handle_auth_response(): Response
    {
        $code = $this->request->get('code');
        $state = $this->request->get('state');
        if (!$code) {
            return $this->handleError('Invalid Code.');
        }

        // See if we can get the access token from the given code
        try {
            $token = $this->service->requestAccessToken($code, $state);

            if (!$token instanceof TokenInterface) {
                return $this->handleError(t('Invalid token provided, please try again later.'));
            }
        } catch (TokenResponseException $e) {
            return $this->handleError(t('Unable to complete authentication, please try again later.'));
        }

        // Try to store the token
        if (!$this->storeToken($token)) {
            return $this->handleError(t('Something went wrong, please try again later.'));
        }

        $this->flash('message', t('Your LinkedIn acount has been attached!'));
        return $this->responseFactory->redirect($this->baseUri(), Response::HTTP_TEMPORARY_REDIRECT);
    }

    /**
     * Deal with reporting errors
     * @param string $error
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function handleError(string $error): Response
    {
        $this->flash('error', [$error]);
        return $this->responseFactory->redirect($this->baseUri(), Response::HTTP_TEMPORARY_REDIRECT);
    }

    /**
     * Get the base uri of this controller
     *
     * @return string
     */
    private function baseUri(): string
    {
        return $this->resolverManager->resolve(['/account/linkedin']);
    }

}
