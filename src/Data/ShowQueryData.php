<?php

namespace Kwidoo\Mere\Data;

use Spatie\LaravelData\Data;

class ShowQueryData extends Data
{
    public function __construct(
        public string $id,
        public ?string $resource = null,
        public ?string $presenter = null
    ) {}
}
