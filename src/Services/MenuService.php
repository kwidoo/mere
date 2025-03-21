<?php

namespace Kwidoo\Mere\Services;

use Kwidoo\Mere\Contracts\MenuService as MenuServiceContract;
use Kwidoo\Mere\Models\MenuItem;

class MenuService implements MenuServiceContract
{
    public function getMenus()
    {
        return MenuItem::all();
    }

    public function getFields(string $name)
    {
        $menuItem = MenuItem::where('name', $name)->first();
        if ($menuItem) {
            return $menuItem->props['fields'];
        }
    }

    public function getRules(string $name)
    {
        $menuItem = MenuItem::where('name', $name)->first();
        if ($menuItem) {
            return $menuItem->props['rules'];
        }
    }
}
