<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Api\Client;

use PortlandLabs\Hosting\Project\Project;
use PortlandLabs\Hosting\Serializer\Serializer;

/**
 * Register API client classes with Hydra types so that they can be easily denormalized into data objects.
 */
class Denormalizer
{

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var array
     */
    protected $mappings = [];

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function registerMapping($type, $class)
    {
        $this->mappings[$type] = $class;
    }

    /**
     * Turns normalized json data in an associative array format (retrieved from the API) into data objects, by
     * looking at their '@type' field
     *
     * @param $data
     */
    public function denormalize($data)
    {
        $class = $this->mappings[$data['@type']];
        if (!$class) {
            throw new \RuntimeException(t('Could not turn API mapping %s into a denormalize class name', $data['@type']));
        }
        $object = $this->serializer->denormalize($data, $class);
        return $object;
    }

}
