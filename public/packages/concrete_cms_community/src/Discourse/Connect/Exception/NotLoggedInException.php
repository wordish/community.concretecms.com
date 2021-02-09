<?php
declare(strict_types=1);

namespace PortlandLabs\Community\Discourse\Connect\Exception;

class NotLoggedInException extends ConnectException
{

    public function __construct()
    {
        parent::__construct('User is not logged in.', 403);
    }

}
