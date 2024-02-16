<?php

declare(strict_types=1);

namespace ConcreteComposer\Encryption;

use Concrete\Core\Config\Repository\Repository;
use ConcreteComposer\TestCase;
use Mockery as M;

class PasswordHasherTest extends TestCase
{
    public function testPasswordValidation()
    {
        $password = 'password';
        $hash = $this->legacyHashedPasswordFor($password);

        $configMock = M::mock(Repository::class);
        $configMock->shouldReceive('get')->andReturnNull();

        $hasher = new PasswordHasher($configMock);

        $this->assertFalse($hasher->checkPassword('foo', $hash));
        $this->assertTrue($hasher->checkPassword($password, $hash));
    }

    public function testLegacyPasswordsGetRehashed()
    {
        $password = 'password';
        $hash = $this->legacyHashedPasswordFor($password);

        $configMock = M::mock(Repository::class);
        $configMock->shouldReceive('get')->with('concrete.user.password.hash_options', [])->andReturn(['cost' => 12]);
        $configMock->shouldReceive('get')->andReturnNull();

        $hasher = new PasswordHasher($configMock);
        $this->assertTrue($hasher->needsRehash($hash));

        // Rehash the password and make sure it verifies plainly without our special wrapper
        $this->assertTrue(password_verify($password, $hasher->hashPassword($password)));
    }

    private function legacyHashedPasswordFor(string $password): string
    {
        return password_hash(md5($password . ':145950'), PASSWORD_BCRYPT, [
            'cost' => 10
        ]);
    }
}
