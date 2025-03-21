<?php

namespace Kwidoo\Mere\Tests\Unit\Services;

use Kwidoo\Mere\Models\MenuItem;
use Kwidoo\Mere\Services\MenuService;
use Kwidoo\Mere\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_all_menus()
    {
        MenuItem::create([
            'name' => 'PropertyList',
            'props' => ['fields' => [['key' => 'name']]],
            'path' => 'property',
            'component' => 'GenericResource',
        ]);

        $service = app(MenuService::class);

        $this->assertCount(1, $service->getMenus());
    }

    public function test_it_returns_fields_from_menu()
    {
        MenuItem::create([
            'name' => 'PropertyList',
            'props' => ['fields' => [['key' => 'name']]],
            'path' => 'property',
            'component' => 'GenericResource',
        ]);

        $fields = app(MenuService::class)->getFields('PropertyList');

        $this->assertEquals([['key' => 'name']], $fields);
    }

    public function test_it_returns_rules_from_menu()
    {
        MenuItem::create([
            'name' => 'PropertyCreate',
            'props' => ['rules' => ['name' => 'required']],
            'path' => 'property',
            'component' => 'GenericCreate',
        ]);

        $rules = app(MenuService::class)->getRules('PropertyCreate');

        $this->assertEquals(['name' => 'required'], $rules);
    }
}
