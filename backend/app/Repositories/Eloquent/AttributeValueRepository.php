<?php

namespace App\Repositories\Eloquent;

use App\Models\AttributeValue;
use App\Repositories\Interfaces\AttributeValueRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AttributeValueRepository extends BaseRepository implements AttributeValueRepositoryInterface
{
    public function __construct(AttributeValue $model)
    {
        parent::__construct($model);
    }

    public function getByAttribute(int $attributeId): Collection
    {
        return $this->model->where('attribute_id', $attributeId)->get();
    }
}
