<?php

namespace Kwidoo\Mere\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;
use Kwidoo\Mere\Contracts\MenuService;
use ReflectionClass;

class FormTransformer extends TransformerAbstract
{
    public function __construct(protected MenuService $menuService) {}

    public function transform(Model $model)
    {
        $class = (new ReflectionClass($model))->getShortName();
        $fields = $this->menuService->getFields($class . 'Update') ?? [];

        $transformed = [];
        foreach ($fields as $field) {
            $transformed[$field['key']] = $model->{$field['key']};

            if (isset($field['type']) && $this->isRelation($field['type'])) {
                $transformed[$field['key']] = $this->transformRelation($model, $field);
            }
        }

        return $transformed;
    }

    protected function isRelation($type)
    {
        $relationTypes = [
            'hasOne',
            'belongsTo',
            'belongsToMany',
            'morphOne',
            'morphTo',
            'morphMany',
            'morphToMany',
            'morphedByMany'
        ];
        return in_array($type, $relationTypes);
    }

    protected function transformRelation(Model $model, array $field)
    {
        $relatedMethod = $field['related'] ?? null;
        if ($relatedMethod && method_exists($model, $relatedMethod)) {
            // Customize the pluck field for display purposes as needed
            return $model->{$relatedMethod}()->pluck('password', 'id');
        }
        return null;
    }
}
