<?php

namespace Concrete5\Community\Query;

interface QueryDriver
{

    /**
     * Convert a given filename into a normalized one
     *
     * @param string $query
     *
     * @return string
     */
    public function normalizeFilename(string $query): string;

    /**
     * Parse a given query into something we can actually send
     * The output of this method will be sent directly to the API as a query
     *
     * @param mixed $query
     *
     * @return mixed
     */
    public function normalizeQuery($query);

}
