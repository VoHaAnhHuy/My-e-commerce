<?php

namespace App\Repositories\Eloquent;

use App\Models\InventoryLocation;
use App\Repositories\Interfaces\InventoryLocationRepositoryInterface;

class InventoryLocationRepository extends BaseRepository implements InventoryLocationRepositoryInterface
{
    public function __construct(InventoryLocation $model)
    {
        parent::__construct($model);
    }
}
