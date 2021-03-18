<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client;

use GuzzleHttp\Client as GuzzleClient;
use League\Url\Url;
use PortlandLabs\Hosting\Api\Client\Query\QueryInterface;
use PortlandLabs\Hosting\Api\Client\Query\Result;

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

    public function executeQuery(QueryInterface $query)
    {
        $queryParams = $query->getQueryParameters();
        $apiUrl = $_ENV['CONCRETE_API_URL'];
        if (!$apiUrl) {
            throw new \RuntimeException(
                t('CONCRETE_API_URL environment variable not defined. You must define this variable to something like https://api.concretecms.com/api.')
            );
        }

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


}
