<?php

namespace Kwidoo\Mere\Http\Controllers;

use Kwidoo\Mere\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController
{
    public function __invoke()
    {
        return MenuItem::all();
    }
}
