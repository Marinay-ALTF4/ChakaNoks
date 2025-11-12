<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'action', 'details', 'ip_address', 'timestamp'];
    protected $useTimestamps = false;

    // Relations
    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id');
    }

    // Methods
    public function logAction($userId, $action, $details = '')
    {
        return $this->insert([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip_address' => $this->request->getIPAddress(),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    public function getUserLogs($userId, $limit = 50)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getRecentLogs($limit = 100)
    {
        return $this->orderBy('timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getLogsByAction($action)
    {
        return $this->where('action', $action)->findAll();
    }

    public function getLogsByDateRange($startDate, $endDate)
    {
        return $this->where('timestamp >=', $startDate)
                    ->where('timestamp <=', $endDate)
                    ->findAll();
    }
}
