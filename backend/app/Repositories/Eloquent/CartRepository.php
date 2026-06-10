<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Repositories\Interfaces\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }

    public function getOrCreateByUser(int $userId): Model
    {
        return $this->model->firstOrCreate(
            ['user_id' => $userId],
            ['user_id' => $userId]
        );
    }

    public function getOrCreateBySession(string $sessionId): Model
    {
        return $this->model->firstOrCreate(
            ['session_id' => $sessionId],
            ['session_id' => $sessionId]
        );
    }
}
