<?php

namespace Concrete5\Community\Integration\Github;

use Carbon\Carbon;
use Concrete5\Community\Query\QueryManager;
use DateTime;
use Generator;
use Iterator;
use OAuth\OAuth2\Service\GitHub;
use RuntimeException;

class GraphqlClient
{

    /**
     * @var \OAuth\OAuth2\Service\GitHub
     */
    private $service;
    /**
     * @var \Concrete5\Community\Query\QueryManager
     */
    private $queryManager;

    /**
     * @var callable
     */
    private $debug;

    public function __construct(GitHub $service, QueryManager $queryManager)
    {
        $this->service = $service;
        $this->queryManager = $queryManager;
    }

    /**
     * Send a query to the github graphql api
     *
     * @param $query
     *
     * @return array
     *
     * @throws \OAuth\Common\Exception\Exception
     * @throws \OAuth\Common\Token\Exception\ExpiredTokenException
     */
    public function query($query, array $params = []): array
    {
        if (is_string($query)) {
            $query = $this->queryManager->get('github', $query);
        }

        $result = $this->service->request('/graphql', 'POST', json_encode([
            'query' => $query,
            'variables' => $params,
        ]));

        if ($this->debug) {
            ($this->debug)($query, $params);
        }

        $resultData = json_decode($result, true);

        return $resultData;
    }

    /**
     * Handle paginating
     *
     * The generator returned by this method has some interesting functionality around dealing with throttling.
     * Either you can use the built in throttling management just by `continue`ing when you see a date object returned:
     *
     *     foreach ($gql->paginate(...) as $page) {
     *         if ($page instanceof \DateTime) continue;
     *         ...
     *     }
     *
     * Or you can manage the throttling in your own subroutine:
     *
     *     foreach ($gql->paginate(...) as $page) {
     *         if ($page instanceof \DateTime) $this->waitUntil($page);
     *         ...
     *     }
     *
     * NOTE:
     * This method does not support queries that need to paginate two result sets at once.
     *
     * @param $query
     * @param string $cursorVariable
     * @param string $cursorPath
     * @param array $params
     *
     * @return \Generator
     * @throws \OAuth\Common\Exception\Exception
     * @throws \OAuth\Common\Token\Exception\ExpiredTokenException
     */
    public function paginate($query, string $cursorVariable, string $cursorPath, array $params): Generator
    {
        // Make sure we have a starting cursor
        $params[$cursorVariable] = $params[$cursorVariable] ?? null;

        do {
            $result = $this->query($query, $params);

            // Handle exceeding the ratelimit
            $rateLimit = $result['data']['rateLimit'] ?? [];
            $remaining = $rateLimit['remaining'] ?? 0;
            $cost = $rateLimit['cost'] ?? 1;

            if ($remaining - $cost <= 1) {
                $reset = $rateLimit['resetAt'] ?? 'now';
                $waitTil = Carbon::make($reset);

                // Handle waiting until we get a new window.
                while(time() < $waitTil->getTimestamp()) {
                    yield $params[$cursorVariable] => $waitTil;

                    // Sleep only happens if the throttle wait isn't managed at a higher level.
                    // This conditional can be true since yield can last any amount of time.
                    if (time() < $waitTil->getTimestamp()) {
                        sleep(5);
                    }
                }
            }

            $pageInfo = array_get($result, $cursorPath, []);
            $hasMore = $pageInfo['hasMore'] ?? false;
            $newCursor = $pageInfo[$cursorVariable] ?? null;

            if ($hasMore && !$newCursor) {
                throw new RuntimeException('Invalid cursor provided.');
            }

            yield $params[$cursorVariable] => $result;
            $params[$cursorVariable] = $newCursor;
        } while($hasMore);
    }

    /**
     * Stream items from a paginated query
     *
     * The path to the items can change depending on the query, this method requires the caller pass in the path to the
     * data as a `dot.string`.
     *
     * NOTE:
     * This method does not support queries that need to paginate two result sets at once.
     *
     * @param \Iterator $paginatedQuery The result of `GraphqlClient->paginate(...)`
     * @param string $dataPath `the.path.to.the.data.array`
     *
     * @return \Generator
     */
    public function streamPagination(Iterator $paginatedQuery, string $dataPath)
    {
        foreach ($paginatedQuery as $cursor => $page) {
            // Handle throttling
            if ($page instanceof DateTime) {
                yield $cursor => $page;
                continue;
            }

            // Yield out the requested items
            $empty = true;
            foreach (array_get($page, $dataPath, []) as $result) {
                $empty = false;
                yield $cursor => $result;
            }

            // Break if the last request didn't have any items
            if ($empty) {
                break;
            }
        }
    }

    /**
     * Debug this client with a function:
     * fn(string $query, array $params)
     *
     * @param callable $debugFunction
     */
    public function debug(callable $debugFunction)
    {
        $this->debug = $debugFunction;
    }

    /**
     * Get the service this client is using
     *
     * @return \OAuth\OAuth2\Service\GitHub
     */
    public function getService()
    {
        return $this->service;
    }
}
