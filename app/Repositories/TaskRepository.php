<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class TaskRepository extends Repository
{
    const DEFAULT_STATUS_ID = 1;
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db =$db;
    }


    /**
     * @return array
     */
    public function getAllAsArray(): ?array
    {
        $stmt = $this->db->prepare('
            SELECT 
                t.*,
                u.username as username,
                s.name as status_name
            FROM tasks t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN statuses s ON t.status_id = s.id
            ORDER BY t.id DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }


    public function getStatus(int $task_id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM statuses WHERE id = :task_id');
        $stmt->execute(['task_id' => $task_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO tasks (user_id, title, description, status_id, created_at, updated_at) 
            VALUES (:user_id, :title, :description, :status_id, NOW(), NOW())
        ');

        $stmt->execute([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'status_id' => self::DEFAULT_STATUS_ID
            ]);

        return (int)$this->db->lastInsertId();
    }
}