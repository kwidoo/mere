<?php

namespace Kwidoo\Mere\Factories;

use Illuminate\Support\Facades\DB;
use Kwidoo\Mere\Contracts\Transactional;

class LaravelTransactions implements Transactional
{
    public function run(callable $callback)
    {
        return DB::transaction($callback);
    }
}
