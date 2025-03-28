<?php

namespace Kwidoo\Mere\Contracts;

interface Transactional
{
    public function run(callable $callback);
}
