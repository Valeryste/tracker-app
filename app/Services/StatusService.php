<?php
declare(strict_types = 1);
namespace App\Services;

use App\Config\Database;
use App\Repositories\StatusRepository;

class StatusService extends Service
{
    private StatusRepository $statusRepository;

    public function __construct()
    {
        $this->statusRepository = new StatusRepository(Database::getConnection());
    }

    public function getAllAsArray(): ?array
    {
        return $this->statusRepository->getAllAsArray() ?: null;
    }

}