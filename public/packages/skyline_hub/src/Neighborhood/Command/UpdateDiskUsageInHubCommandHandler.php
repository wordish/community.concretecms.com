<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Doctrine\DBAL\Connection;
use PortlandLabs\Skyline\Entity\Site;

class UpdateDiskUsageInHubCommandHandler
{

    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function __invoke(UpdateDiskUsageInHubCommand $command)
    {
        $this->db->beginTransaction();
        foreach ($command->getSizes() as $accountHandle => $bytesUsed) {
            $this->db->update(
                'SkylineSites',
                ['bytesUsed' => $bytesUsed],
                ['handle' => $accountHandle, 'neighborhood' => $command->getNeighborhood()]
            );
        }
        $this->db->commit();
    }


}
