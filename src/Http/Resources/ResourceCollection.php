<?php

namespace Kwidoo\Mere\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;

class ResourceCollection extends BaseResourceCollection
{
    protected array $fields = [];
    protected string $label = 'resource';
    protected array $actions = [
        'create' => true,
        'edit' => true,
        'delete' => true,
    ];
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...$this->collection,
            'resource' => [
                'label' => $this->label,
                'fields' => $this->fields,
                'actions' => $this->actions,
            ],
        ];
    }

    public function fields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function canCreate(bool $create): self
    {
        $this->actions['create'] = $create;

        return $this;
    }

    public function canEdit(bool $edit): self
    {
        $this->actions['edit'] = $edit;

        return $this;
    }

    public function canDelete(bool $delete): self
    {
        $this->actions['delete'] = $delete;

        return $this;
    }
}
