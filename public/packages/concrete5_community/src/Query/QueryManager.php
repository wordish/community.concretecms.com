<?php

namespace Concrete5\Community\Query;

use Concrete\Core\Application\Application;
use Concrete5\Community\Integration\Github\GithubQueryDriver;
use Illuminate\Support\Manager;
use RuntimeException;
use Throwable;

class QueryManager extends Manager
{

    private const QUERY_FOLDER = __DIR__ . '/../../queries';

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * @param string $driverHandle
     * @param string $queryHandle
     *
     * @return mixed
     */
    public function get(string $driverHandle, string $queryHandle)
    {
        $driver = $this->driver($driverHandle);
        $file = $driver->normalizeFilename($queryHandle);
        $baseDir = self::QUERY_FOLDER;

        return $driver->normalizeQuery($this->loadFile("{$baseDir}/{$driverHandle}/{$file}"));
    }

    /**
     * @param $file
     *
     * @return mixed
     */
    private function loadFile($file)
    {
        try {
            return include $file;
        } catch (Throwable $e) {
            throw new RuntimeException('Unable to load query.');
        }
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        throw new RuntimeException('Driver must be provided when accessing queries.');
    }

    /**
     * Driver creator
     *
     * @return \Concrete5\Community\Integration\Github\GithubQueryDriver
     */
    public function createGithubDriver()
    {
        return $this->app->make(GithubQueryDriver::class);
    }
}
