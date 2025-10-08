<?php

declare(strict_types =1 );

namespace App\Models;

class Task extends Model
{
    private int $id;
    private int $user_id;
    private int $status_id;
    private string $title;
    private string $description;
    private string $admin_response;


    public function __construct(
        int $id,
        int $user_id,
        int $status_id,
        string $title,
        string $description,
        string $admin_response = null
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->status_id = $status_id;
        $this->title = $title;
        $this->description = $description;
        $this->admin_response = $admin_response;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getStatusId(): int
    {
        return $this->status_id;
    }

    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAdminResponse(): string
    {
        return $this->admin_response;
    }

    public function setAdminResponse(string $admin_response): void
    {
        $this->admin_response = $admin_response;
    }

}