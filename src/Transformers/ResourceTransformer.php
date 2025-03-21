<?php

namespace Kwidoo\Mere\Transformers;

use League\Fractal\TransformerAbstract;
use Kwidoo\Mere\Contracts\MenuService;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

/**
 * Class TenantTransformer.
 *
 * @package namespace App\Transformers;
 */
class ResourceTransformer extends TransformerAbstract
{
    public function __construct(protected MenuService $menuService) {}
    /**
     * Transform the Model entity.
     *
     * @param Model $model
     *
     * @return array
     */
    public function transform(Model $model)
    {
        $class = (new ReflectionClass($model))->getShortName();
        $fields = $this->menuService->getFields($class . 'List') ?? [];

        $transformed = [];
        foreach ($fields as $field) {
            $transformed[$field['key']] = $model->{$field['key']};
        }

        return $transformed;
    }
}
