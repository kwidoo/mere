<?php

namespace Kwidoo\Mere\Contracts;

interface MenuService
{
    public function getMenus();

    public function getFields(string $name);
}
