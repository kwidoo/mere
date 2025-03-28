<?php

namespace Kwidoo\Mere\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class ListQueryData extends Data
{
    public function __construct(
        public ?string $resource = null,
        #[MapInputName('per_page')]
        public readonly ?int $perPage = null,
        public readonly array $columns = ['*'],
        public ?string $presenter = null,
    ) {}
}
