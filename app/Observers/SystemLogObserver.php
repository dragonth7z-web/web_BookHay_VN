<?php

namespace App\Observers;

use App\Models\SystemLog;
use Illuminate\Database\Eloquent\Model;

class SystemLogObserver
{
    /**
     * Lấy tên model hiển thị
     */
    private function getModelName(Model $model): string
    {
        return class_basename($model);
    }

    /**
     * Helper log
     */
    private function log(string $action, Model $model, ?array $old = null, ?array $new = null)
    {
        $modelName = $this->getModelName($model);
        $id = $model->getKey();

        $actionText = match ($action) {
            'created' => 'Thêm mới',
            'updated' => 'Cập nhật',
            'deleted' => 'Xóa',
            default => $action
        };

        $description = "{$actionText} [{$modelName}] #{$id}";

        if ($action === 'created' && method_exists($model, 'getAttribute')) {
            if ($modelName === 'Book') {
                $description .= " ({$model->title})";
            } elseif ($modelName === 'Order') {
                $description .= " (Mã: {$model->order_number})";
            } elseif ($modelName === 'PurchaseOrder') {
                $description .= " (Tổng: " . number_format((float) $model->total_amount, 0, ',', '.') . " ₫)";
            }
        }

        SystemLog::ghi(
            type: 'data',
            action: $action,
            description: $description,
            level: $action === 'deleted' ? 'warning' : 'info',
            objectType: $modelName,
            objectId: $id,
            oldData: $old,
            newData: $new
        );
    }

    public function created(Model $model)
    {
        $this->log('created', $model, null, $model->toArray());
    }

    public function updated(Model $model)
    {
        $old = [];
        $new = [];
        foreach ($model->getDirty() as $key => $value) {
            $old[$key] = $model->getOriginal($key);
            $new[$key] = $value;
        }

        if (!empty($new)) {
            $this->log('updated', $model, $old, $new);
        }
    }

    public function deleted(Model $model)
    {
        $this->log('deleted', $model, $model->toArray(), null);
    }
}
