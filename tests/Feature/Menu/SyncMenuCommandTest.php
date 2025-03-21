<?php

namespace Kwidoo\Mere\Tests\Feature\Menu;

use Kwidoo\Mere\Models\MenuItem;
use Kwidoo\Mere\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class SyncMenuCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_menu_items_from_model_and_appends()
    {
        // Register fake model
        class_alias(\Kwidoo\Mere\Tests\Fixtures\FakeModel::class, 'App\Models\Property');

        Artisan::call('mere:sync-menu', ['--resource' => 'Property']);

        $this->assertDatabaseHas('menu_items', ['name' => 'PropertyList']);
        $this->assertDatabaseHas('menu_items', ['name' => 'PropertyCreate']);
        $this->assertDatabaseHas('menu_items', ['name' => 'PropertyUpdate']);

        $menuItem = MenuItem::where('name', 'PropertyList')->first();
        $this->assertNotEmpty($menuItem->props['fields']);
        $this->assertTrue($menuItem->props['actions']['create']);
    }
}
