<?php

declare(strict_types=1);

namespace PortlandLabs\Community\Discourse\Connect\Exception;

class SignatureNotValidException extends ConnectException
{
    public function __construct()
    {
        parent::__construct('Invalid signature provided.', 400);
    }

}
