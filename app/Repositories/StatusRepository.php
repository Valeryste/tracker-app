<?php

declare(strict_types=1);

namespace App\Repositories;
use PDO;

class StatusRepository extends Repository
{
    public function getAllAsArray(): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, slug FROM statuses');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }
}