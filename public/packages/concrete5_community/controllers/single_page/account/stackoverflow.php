<?php

namespace Concrete\Package\Concrete5Community\Controller\SinglePage\Account;

use Concrete\Core\Database\DatabaseManager;
use Concrete\Core\Http\ResponseFactoryInterface;
use Concrete\Core\Page\Controller\AccountPageController;
use Concrete\Core\Url\Resolver\Manager\ResolverManager;
use Concrete\Core\User\User;
use Concrete5\Community\Integration\Stackoverflow\StackoverflowService;
use Concrete5\Community\Integration\Stackoverflow\UserDataPopulator;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\OAuth2\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;

class Stackoverflow extends AccountPageController
{

    /**
     * @var \Concrete\Core\Http\ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var \Concrete5\Community\Integration\Stackoverflow\UserDataPopulator
     */
    private $populator;

    /**
     * @var \Concrete\Core\Database\DatabaseManager
     */
    private $database;

    /**
     * @var \Concrete5\Community\Integration\Stackoverflow\StackoverflowService
     */
    private $stackoverflow;

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
        StackoverflowService $stackoverflow,
        ResolverManager $resolverManager
    ) {
        parent::__construct($c);
        $this->responseFactory = $responseFactory;
        $this->populator = $populator;
        $this->database = $database;
        $this->stackoverflow = $stackoverflow;
        $this->resolverManager = $resolverManager;
    }

    /**
     * Handle setting data for basic display
     */
    public function view(): void
    {
        $user = $this->app->make(User::class)->getUserInfoObject();
        $id = $bronze = $silver = $gold = $reputation = 0;

        if ($user) {
            $id = $user->getAttribute('stackoverflow_user_id');
            $bronze = $silver = $gold = $reputation = 0;

            if ($id) {
                $bronze = $user->getAttribute('stackoverflow_badges_bronze');
                $silver = $user->getAttribute('stackoverflow_badges_silver');
                $gold = $user->getAttribute('stackoverflow_badges_gold');
                $reputation = $user->getAttribute('stackoverflow_reputation');
            }
        }

        $this->set('id', $id);
        $this->set('bronze', $bronze);
        $this->set('silver', $silver);
        $this->set('gold', $gold);
        $this->set('reputation', $reputation);
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
        if ($this->token->validate('stackoverflow_connect', $token)) {
            $url = $this->stackoverflow->getAuthorizationUri([]);
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
    public function storeToken(TokenInterface $token): bool
    {
        $user = $this->app->make(User::class)->getUserInfoObject();
        if ($user && $this->populator->populate($user, $token)) {
            $db = $this->database->connection();

            // Clear out old binding
            $db->delete('OauthUserMap', [
                'user_id' => $user->getUserID(),
                'namespace' => 'integration_stackoverflow',
            ]);

            // Make new binding
            $db->insert('OauthUserMap', [
                'user_id' => $user->getUserID(),
                'binding' => $token->getAccessToken(),
                'namespace' => 'integration_stackoverflow',
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
        if (!$code) {
            return $this->handleError('Invalid Code.');
        }

        // See if we can get the access token from the given code
        try {
            $token = $this->stackoverflow->requestAccessToken($code);

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

        $this->flash('message', t('Your StackOverflow acount has been attached!'));
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
        return $this->resolverManager->resolve(['/account/stackoverflow']);
    }

}
