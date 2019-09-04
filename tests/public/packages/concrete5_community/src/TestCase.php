<?php
declare(strict_types=1);

namespace Concrete5\Community;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{

    // Integrate Mockery bindings so that we can use mockery
    use MockeryPHPUnitIntegration;
}
