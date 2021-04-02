<?php

namespace PortlandLabs\Hosting\Project\Command;

class CreateLagoonProjectCommand extends CreateProjectCommand
{

    /** @var int[] */
    public array $adminUsers;

    /** @var int[] */
    public array $users;

    public string $gitUrl;

    public string $productionBranch;

    /** @var string[] */
    public array $stageBranches;
}
