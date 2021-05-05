<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api;

use Concrete\Core\Http\Request;
use Concrete\Core\Http\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\RequestOptions;

use function GuzzleHttp\Psr7\stream_for;

class Token
{
    protected string $jwt = '';

    public function renew(Client $client): void
    {
        $username = $_ENV['CONCRETE_API_USERNAME'];
        $password = $_ENV['CONCRETE_API_PASSWORD'];

        try {
            $response = $client->post(
                $_ENV['CONCRETE_API_URL'] . '/login',
                [
                    RequestOptions::TIMEOUT => 3,
                    RequestOptions::HEADERS => [
                        'Content-Type' => 'application/json'
                    ],
                    RequestOptions::BODY => Utils::streamFor(json_encode(['email' => $username, 'password' => $password]))
                ]
            );

            $data = json_decode($response->getBody()->getContents(), true) ?: [];
            $token = $data['jwt'];

            $this->jwt = $token;
        } catch (ServerException $e) {
            throw new \RuntimeException('Unable to authenticate, unexpected server error.');
        } catch (RequestException $e) {
            switch ($e->getCode()) {
                case Response::HTTP_BAD_REQUEST:
                case Response::HTTP_UNAUTHORIZED:
                case Response::HTTP_FORBIDDEN:
                    throw new \RuntimeException('Invalid authentication credentials provided. Check configuration. ' . $e->getMessage());
                default:
                    throw new \RuntimeException('Unexpected request error: ' . $e->getCode());
            }
        } catch (\Throwable $e) {
            throw new \RuntimeException('Unable to authenticate, unexpected error.');
        }
    }

    public function __toString()
    {
        return $this->jwt ? "Bearer {$this->jwt}" : '';
    }
}
