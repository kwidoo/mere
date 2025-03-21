<?php

namespace Kwidoo\Mere\Http\Controllers;

use Kwidoo\Mere\Contracts\MenuService;

class MenuController
{
    public function __construct(protected MenuService $service) {}

    public function __invoke()
    {
        return $this->service->getMenus();
    }
}
