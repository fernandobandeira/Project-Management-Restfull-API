<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\User;
use League\Fractal\TransformerAbstract;

class ProjectOwnerTransformer extends TransformerAbstract
{
    public function transform(User $owner)
    {
        return [
            'owner_id' => $owner->id,
            'name' => $owner->name,
        ];
    }
}