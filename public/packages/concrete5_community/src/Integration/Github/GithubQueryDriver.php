<?php

namespace Concrete5\Community\Integration\Github;

use Concrete5\Community\Query\QueryDriver;

class GithubQueryDriver implements QueryDriver
{

    /**
     * Convert a given filename into a normalized one
     *
     * @param string $query
     *
     * @return string
     */
    public function normalizeFilename(string $query): string
    {
        return $query . '.gql.php';
    }

    /**
     * Parse a given query into something we can actually send
     * The output of this method will be sent directly to the API as a query
     *
     * @param mixed $query
     *
     * @return mixed
     */
    public function normalizeQuery($query)
    {
        // Add ratelimiting query
        foreach ($query as &$group) {
            $group['rateLimit'] = [
                'limit',
                'cost',
                'remaining',
                'resetAt',
            ];
        }

        return $this->serializeQuery($query);
    }

    /**
     * Convert an array into an actual graphql query
     *
     * GraphQL queries look a lot like json but it's a totally distinct format.
     * We basically output each entry in the array and wrap sub arrays with `{` and `}` strings. Thankfully newlines
     * aren't required.
     *
     * @param array $query
     * @param array $lines
     *
     * @return string
     */
    private function serializeQuery(array $query, &$lines = [])
    {
        foreach ($query as $key => $value) {
            if (!is_array($value)) {
                $lines[] = $value;
            } else {
                $lines[] = $key . ' {';
                $this->serializeQuery($value, $lines);
                $lines[] = '}';
            }
        }

        return implode(' ', $lines);
    }
}
