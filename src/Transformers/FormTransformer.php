<?php

namespace Kwidoo\Mere\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Property;

/**
 * Class FormTransformer.
 *
 * @package namespace App\Transformers;
 */
class FormTransformer extends TransformerAbstract
{
    /**
     * Transform the Property entity.
     *
     * @param \App\Models\Property $model
     *
     * @return array
     */
    public function transform(Property $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
