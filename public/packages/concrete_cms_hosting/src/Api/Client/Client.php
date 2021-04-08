<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ServerException;
use League\Url\Url;
use PortlandLabs\Hosting\Api\Client\Query\QueryInterface;
use PortlandLabs\Hosting\Api\Client\Query\Result;
use PortlandLabs\Hosting\Api\Client\Query\SearchQueryInterface;

class Client
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Denormalizer
     */
    protected $denormalizer;

    public function __construct(GuzzleClient $client, Denormalizer $denormalizer)
    {
        $this->client = $client;
        $this->denormalizer = $denormalizer;
    }

    private function getApiUrl()
    {
        $apiUrl = $_ENV['CONCRETE_API_URL'];
        if (!$apiUrl) {
            throw new \RuntimeException(
                t('CONCRETE_API_URL environment variable not defined. You must define this variable to something like https://api.concretecms.com/api.')
            );
        }
        return $apiUrl;
    }

    public function search(SearchQueryInterface $query)
    {
        $queryParams = $query->getQueryParameters();

        $apiUrl = $this->getApiUrl();
        $url = Url::createFromUrl($apiUrl . $query->getEndpoint());
        $url->getQuery()->set($queryParams);

        $response = $this->client->get((string) $url);

        $data = json_decode((string) $response->getBody(), true);

        $result = new Result();
        $result->setTotalItems($data['hydra:totalItems']);
        foreach($data['hydra:member'] as $member) {
            $object = $this->denormalizer->denormalize($member);
            $result->add($object);
        }

        return $result;
    }

    public function getResource(QueryInterface $query)
    {
        $apiUrl = $this->getApiUrl();
        $url = $apiUrl . $query->getEndpoint();

        $response = $this->client->get($url);

        $data = json_decode((string) $response->getBody(), true);
        $object = $this->denormalizer->denormalize($data);

        return $object;
    }

    public function createResource(string $endpoint, array $data)
    {
        $apiUrl = $this->getApiUrl();
        $url = $apiUrl . $endpoint;
        try {
            return $this->client->post($url, ['headers' => [
                'Content-Type' => 'application/json'
            ], 'json' => $data]);
        } catch(\Exception $e) {
            throw new \Exception($e->getResponse()->getBody()->getContents());
        }
    }

    public function updateResource(string $endpoint, array $data)
    {
        $apiUrl = $this->getApiUrl();
        $url = $apiUrl . $endpoint;
        try {
            return $this->client->put($url, ['headers' => [
                'Content-Type' => 'application/json'
            ], 'json' => $data]);
        } catch(\Exception $e) {
            throw new \Exception($e->getResponse()->getBody()->getContents());
        }
    }


    public function __call($name, $arguments)
    {
        $apiUrl = $this->getApiUrl();
        $url = $apiUrl . $arguments[0];
        $arguments[0] = $url;
        return $this->client->$name(...$arguments);
    }
}
