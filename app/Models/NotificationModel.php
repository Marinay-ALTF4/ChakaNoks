<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table         = 'notifications';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'user_id',
        'type',
        'data',
        'read_at',
        'created_at',
    ];

    public function unreadForUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->where('read_at', null)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
