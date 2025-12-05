<?php

namespace App\Services;

use App\Models\NotificationModel;
use App\Models\UserModel;
use Config\Services;
use CodeIgniter\I18n\Time;

class NotificationService
{
    public function __construct(
        private readonly NotificationModel $notificationModel = new NotificationModel(),
        private readonly UserModel $userModel = new UserModel(),
    ) {
    }

    /**
     * Persist in-system notifications and optionally dispatch email copies.
     */
    public function notify(int|array $userIds, string $type, array $data, bool $sendEmail = false): void
    {
        $userIds = (array) $userIds;

        foreach ($userIds as $userId) {
            $this->notificationModel->insert([
                'user_id'    => $userId,
                'type'       => $type,
                'data'       => json_encode($data, JSON_THROW_ON_ERROR),
                'created_at' => Time::now('Asia/Manila')->toDateTimeString(),
            ]);

            if ($sendEmail) {
                $user = $this->userModel->find($userId);
                if (! $user || empty($user['email'])) {
                    continue;
                }

                $email = Services::email();
                $email->setTo($user['email']);
                $email->setSubject('Logistics Update: ' . ucfirst(str_replace('_', ' ', $type)));
                $email->setMessage(view('emails/logistics_notification', ['user' => $user, 'data' => $data]));
                $email->send();
            }
        }
    }
}
