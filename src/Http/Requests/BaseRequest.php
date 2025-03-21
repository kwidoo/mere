<?php

namespace Kwidoo\Mere\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Kwidoo\Mere\Contracts\MenuService;
use Illuminate\Support\Str;

class BaseRequest extends FormRequest
{
    public function __construct(protected MenuService $menuService, array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = 'Create';
        if ($this->method() === 'PUT') {
            $type = 'Update';
        }
        return $this
            ->menuService
            ->getRules(
                Str::studly(
                    Str::singular(
                        $this->segment(2)
                    ) . $type
                )
            );
    }
}
