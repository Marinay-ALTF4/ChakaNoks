<?php

use App\Models\DeliveryModel;

$statuses = DeliveryModel::getStatusFlow();
?>
<div class="timeline mb-3">
    <div class="d-flex justify-content-between flex-wrap">
        <?php foreach ($statuses as $status): ?>
            <?php
                $timestamp = $timeline[$status] ?? null;
                $label = ucfirst(str_replace('_', ' ', $status));
                $isComplete = ! empty($timestamp) && $status !== DeliveryModel::STATUS_CANCELLED;
            ?>
            <div class="text-center flex-fill p-2">
                <div class="rounded-circle mx-auto mb-2" style="width: 14px; height: 14px; background-color: <?= $isComplete ? '#198754' : '#ced4da' ?>;"></div>
                <div class="fw-semibold small">
                    <?= esc($label) ?>
                </div>
                <div class="text-muted small">
                    <?= $timestamp ? date('M d, Y H:i', strtotime($timestamp)) : '—' ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
