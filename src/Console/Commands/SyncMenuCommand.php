<?php

namespace Kwidoo\Mere\Console\Commands;

use Illuminate\Console\Command;
use Kwidoo\Mere\Models\MenuItem;
use Illuminate\Support\Str;

class SyncMenuCommand extends Command
{
    protected $signature = 'mere:sync-menu {--resource=} {--from=model} {--component=}';
    protected $description = 'Generate or update menu items for a resource';

    public function handle()
    {
        $resource = $this->option('resource');

        if (!$resource) {
            $this->error('Please provide a --resource=YourModelName');
            return;
        }

        $componentOverride = $this->option('component');

        $modelClass = "App\\Models\\{$resource}";
        if (!class_exists($modelClass)) {
            $this->error("Model $modelClass not found.");
            return;
        }

        $model = new $modelClass();
        $baseFields = collect($model->getFillable())
            ->merge($model->getAppends()) // include appended accessors
            ->unique()
            ->values();

        $fields = $baseFields->map(function ($f) {
            return [
                'key' => $f,
                'label' => Str::title(str_replace('_', ' ', $f)),
                'sortable' => true,
                'visible' => true,
            ];
        })->toArray();


        $menuItems = [
            "{$resource}List" => [
                'fields' => $fields,
                'actions' => [
                    'create' => true,
                    'edit' => true,
                    'delete' => true,
                ],
                'component' => 'GenericResource',
                'path' => Str::kebab(Str::plural($resource)),
            ],
            "{$resource}Create" => [
                'fields' => $fields,
                'rules' => [],
                'component' => 'GenericCreate',
                'path' => Str::kebab(Str::plural($resource)) . '/create',

            ],
            "{$resource}Update" => [
                'fields' => $fields,
                'rules' => [],
                'component' => 'GenericUpdate',
                'path' => Str::kebab(Str::plural($resource)) . '/edit/:id',

            ],
        ];

        foreach ($menuItems as $name => $props) {
            $path = $props['path'];
            unset($props['path']);

            $component = $props['component'] ?? 'Layout';
            $componentOverride ?: ($props['component'] ?? 'Layout');

            unset($props['component']);

            MenuItem::updateOrCreate(
                ['name' => $name],
                [
                    'name' => $name,
                    'props' => ['label' => $path, ...$props],
                    'path' => "/{$path}",
                    'component' => $component,
                ]
            );
            $this->info("Synced menu item: $name");
        }

        $this->info("Done.");
    }

    protected function extractRules(string $formRequestClass): array
    {
        if (!class_exists($formRequestClass)) {
            return [];
        }

        $request = app($formRequestClass);
        return method_exists($request, 'rules') ? $request->rules() : [];
    }
}
