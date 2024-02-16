<?php

declare(strict_types=1);

namespace PortlandLabs\Community\Discourse\Connect\Exception;

class InvalidDataException extends ConnectException
{
    public function __construct()
    {
        parent::__construct('Invalid data provided.', 400);
    }

}
