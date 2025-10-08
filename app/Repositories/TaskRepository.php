<?php

declare(strict_types=1);

namespace App\Repositories;

use Exception;
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
    public function getAllAsArray(string $sort = 'newest', ?string $statusForFilter = null): ?array
    {
        $order = ($sort === 'oldest') ? 'ASC' : 'DESC';

        $sql = "
            SELECT 
                t.*,
                u.username as username,
                s.name as status_name,
                s.slug as status,
                GROUP_CONCAT(tags.slug) as tags
            FROM tasks t
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN statuses s ON t.status_id = s.id
            LEFT JOIN task_tags tt ON t.id = tt.task_id
            LEFT JOIN tags ON tt.tag_id = tags.id
        ";

        if ($statusForFilter !== null && $statusForFilter !== '') {
            $sql .= " WHERE s.id = :status";
        }

        $sql .= " GROUP BY t.id ORDER BY t.created_at $order, t.id $order";

        $stmt = $this->db->prepare($sql);

        if ($statusForFilter !== null && $statusForFilter !== '') {
            $stmt->bindParam(':status', $statusForFilter, PDO::PARAM_STR);
        }

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

    /**
     * @throws Exception
     */
    public function update(array $data): bool
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare('
            UPDATE tasks 
            SET status_id = (SELECT id FROM statuses WHERE slug = :status),
                admin_response = :admin_response,
                updated_at = NOW()
            WHERE id = :id
            ');

            $stmt->execute([
                ':id' => $data['task_id'],
                ':status' => $data['status'],
                ':admin_response' => $data['admin_response'] === '' ? null : $data['admin_response']
            ]);

            if (!empty($data['tags'])) {
                $stmt = $this->db->prepare('DELETE FROM task_tags WHERE task_id = :task_id');
                $stmt->execute([':task_id' => $data['task_id']]);

                $tagsStmt = $this->db->prepare('
                    INSERT INTO task_tags (task_id, tag_id) 
                    VALUES (:task_id, (SELECT id FROM tags WHERE slug = :tag_slug))
                ');

                foreach ($data['tags'] as $tagSlug) {
                    $tagsStmt->execute([
                        ':task_id' => $data['task_id'],
                        ':tag_slug' => $tagSlug
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception('Ошибка обновления задачи: ' . $e->getMessage());
        }
    }
}