<?php

namespace PortlandLabs\Hosting\Software\Command;

class UpdateConcreteReleaseCommand extends CreateConcreteReleaseCommand
{

    /**
     * @var string
     */
    protected $id;

    /**
     * UpdateConcreteReleaseCommand constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }





}
