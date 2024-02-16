<?php

declare(strict_types=1);

namespace PortlandLabs\Community\Discourse\Connect\Exception;

use RuntimeException;

abstract class ConnectException extends RuntimeException
{
    public static function notLoggedIn(): NotLoggedInException
    {
        return new NotLoggedInException();
    }

    public static function invalidData(): InvalidDataException
    {
        return new InvalidDataException();
    }

    public static function invalidSignature(): SignatureNotValidException
    {
        return new SignatureNotValidException();
    }

}
