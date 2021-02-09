<?php

declare(strict_types=1);

namespace PortlandLabs\Community\Discourse\Connect;

use Concrete\Core\Http\Request;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Routing\RedirectResponse;
use Concrete\Core\User\PostLoginLocation;
use Concrete\Core\User\User;
use League\Url\Components\Query;
use League\Url\Url;
use PortlandLabs\Community\Discourse\Connect\Exception\ConnectException;
use PortlandLabs\Community\Discourse\Connect\Exception\InvalidDataException;
use PortlandLabs\Community\Discourse\Connect\Exception\NotLoggedInException;
use ProxyManager\Signature\Exception\InvalidSignatureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class ConnectController
{
    /**
     * @var ResponseFactory
     */
    private $factory;

    /**
     * @var User
     */
    private $loggedInUser;

    /**
     * @var PostLoginLocation
     */
    private $postLogin;

    /**
     * @var UserTransformer
     */
    private UserTransformer $transformer;

    public function __construct(
        ResponseFactory $factory,
        User $user,
        PostLoginLocation $postLogin,
        UserTransformer $transformer
    ) {
        $this->factory = $factory;
        $this->loggedInUser = $user;
        $this->postLogin = $postLogin;
        $this->transformer = $transformer;
    }

    /**
     * Handle DiscourseConnect attempts
     *
     * @param Request $request
     *
     * @return SymfonyResponse Either a json response or a redirect response
     */
    public function connect(Request $request): SymfonyResponse
    {
        $data = $request->get('sso', '');
        $signature = $request->get('sig', '');
        try {
            return $this->handleConnect($data, $signature);
        } catch (NotLoggedInException $e) {
            return $this->handleLoginRedirect($request, $data, $signature);
        } catch (Throwable $e) {
            return $this->handleFailure($e);
        }
    }

    /**
     * Parse and validate a given connect request
     *
     * @param string $data
     * @param string $signature
     *
     * @return RedirectResponse
     *
     * @throws InvalidSignatureException If the signature is invalid
     * @throws InvalidDataException If the given data is not valid
     * @throws NotLoggedInException If the current user is not logged in
     */
    private function handleConnect(string $data, string $signature): RedirectResponse
    {
        // Validate signature, it's important that we do this first because it weeds out the vast
        // majority of failure edge cases.
        $this->validateSignature($data, $signature);

        // Check if the current user is logged in
        if (!$this->loggedInUser->checkLogin()) {
            throw ConnectException::notLoggedIn();
        }

        // Decode into a query string and extract relevant info
        $data = base64_decode($data);
        $query = with(new Query($data))->toArray();

        $nonce = $query['nonce'] ?? '';
        $returnUrl = $query['return_sso_url'] ?? '';

        if (!$nonce || !$returnUrl) {
            throw ConnectException::invalidData();
        }

        $userData = $this->getUserData();
        $userData['nonce'] = $nonce;

        // Convert the user data array to a query string
        $query = new Query($userData);
        $dataString = base64_encode((string) $query);

        // Generate a new signature
        $signature = $this->signature($dataString);

        $url = Url::createFromUrl($returnUrl);
        $url->setQuery(['sso' => $dataString,'sig' => $signature]);

        return $this->factory->redirect($url, 302);
    }

    /**
     * Validate the given DiscourseConnect signature
     *
     * @param string $data
     * @param string $signature
     *
     * @return void
     *
     * @throws InvalidSignatureException If the signature is invalid
     */
    private function validateSignature(string $data, string $signature): void
    {
        // Compare the two signatures
        if (!hash_equals($this->signature($data), $signature)) {
            throw ConnectException::invalidSignature();
        }
    }

    private function signature(string $data): string
    {
        return hash_hmac('sha256', $data, getenv('DISCOURSE_SECRET'));
    }

    /**
     * Output thrown exceptions for a user to be able to debug
     * This method will never be called unless discourse has a major misconfiguration or a user is messing around
     * without valid signatures.
     *
     * @param Throwable $e
     * @return JsonResponse
     */
    private function handleFailure(Throwable $e): JsonResponse
    {
        $code = 500;
        $result = [
            'success' => false,
            'error' => [
                'code' => 0,
                'message' => 'Unknown Error',
            ]
        ];

        // Handle exceptions that we throw
        if ($e instanceof ConnectException) {
            $result['error']['message'] = $e->getMessage();
            $result['error']['code'] = $e->getCode();
            $code = $e->getCode();
        }

        return $this->factory->json($result, $code);
    }

    /**
     * Handle redirecting to login if the user is not currently logged in
     *
     * This method needs to make sure to set the post login location so that a user properly flows back
     * to the forums after successfuly logging in.
     *
     * @param Request $request
     * @param string $data
     * @param string $signature
     *
     * @return RedirectResponse|SymfonyResponse
     */
    private function handleLoginRedirect(Request $request, string $data, string $signature)
    {
        $this->postLogin->setSessionPostLoginUrl($request->getUri());
        return $this->factory->redirect('/login', 302);
    }

    /**
     * Get the datq associated with the logged in user
     *
     * @return array
     */
    private function getUserData(): array
    {
        return $this->transformer->transform($this->loggedInUser);
    }

}
