<?php

namespace App\Services;

use App\Models\ActivityLogModel;
use CodeIgniter\I18n\Time;

class ActivityLogService
{
    public function __construct(private readonly ActivityLogModel $model = new ActivityLogModel())
    {
    }

    public function log(string $action, string $model, ?int $modelId, array $meta = [], ?int $userId = null): void
    {
        $this->model->insert([
            'user_id'    => $userId,
            'action'     => $action,
            'model'      => $model,
            'model_id'   => $modelId,
            'meta'       => json_encode($meta, JSON_THROW_ON_ERROR),
            'created_at' => Time::now('Asia/Manila')->toDateTimeString(),
        ]);
    }
}
