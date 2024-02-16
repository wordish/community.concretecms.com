<?php

declare(strict_types=1);

namespace ConcreteComposer\Encryption;

class PasswordHasher extends \Concrete\Core\Encryption\PasswordHasher
{
    /**
     * Check whether our old upgraded password algorithm from way back when validates. If it does the password will be
     * rehashed.
     */
    public function checkPassword($password, $storedHash)
    {
        return password_verify(md5($password . ':145950'), $storedHash) || parent::checkPassword($password, $storedHash);
    }
}
