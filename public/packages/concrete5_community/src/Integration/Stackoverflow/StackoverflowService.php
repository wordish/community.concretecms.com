<?php

namespace Concrete5\Community\Integration\Stackoverflow;

use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Token\TokenInterface;
use OAuth\OAuth2\Service\AbstractService;
use OAuth\OAuth2\Token\StdOAuth2Token;
use function GuzzleHttp\Psr7\parse_query;

class StackoverflowService extends AbstractService
{
    /**
     * We have to define these constants in order to use these scopes due to the way the OAuth\Common dependency works
     * Currently we're only using `no_expiry` as we only need public info
     *
     * https://api.stackexchange.com/docs/authentication#scope
     */
    public const SCOPE_NO_EXPIRY = 'no_expiry';
    public const SCOPE_READ_INBOX = 'read_inbox';
    public const SCOPE_WRITE_ACCESS = 'write_access';
    public const SCOPE_PRIVATE_INFO = 'private_info';

    /**
     * Request a token that never expires
     *
     * @var array
     */
    protected $scopes = [self::SCOPE_NO_EXPIRY];

    /**
     * Parses the access token response and returns a TokenInterface.
     *
     * @param string $responseBody
     *
     * @return TokenInterface
     */
    protected function parseAccessTokenResponse($responseBody): TokenInterface
    {
        $data = parse_query($responseBody);
        return new StdOAuth2Token($data['access_token'], null, StdOAuth2Token::EOL_NEVER_EXPIRES);
    }

    /**
     * Returns the authorization API endpoint.
     *
     * @return UriInterface
     */
    public function getAuthorizationEndpoint(): UriInterface
    {
        return new Uri('https://stackoverflow.com/oauth');
    }

    /**
     * Returns the access token API endpoint.
     *
     * @return UriInterface
     */
    public function getAccessTokenEndpoint(): UriInterface
    {
        return new Uri('https://stackoverflow.com/oauth/access_token');
    }

    public function getAuthorizationMethod(): int
    {
        return self::AUTHORIZATION_METHOD_QUERY_STRING;
    }
}
