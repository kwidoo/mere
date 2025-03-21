<?php

namespace Kwidoo\Mere\Tests\Fixtures;

use Illuminate\Pagination\LengthAwarePaginator;
use Kwidoo\Mere\Contracts\MenuService;
use Kwidoo\Mere\Services\BaseService;

class FakeService extends BaseService
{
    public function __construct()
    {
        parent::__construct(app(MenuService::class), app(FakeRepository::class));
    }

    protected function eventKey(): string
    {
        return 'property';
    }

    public function getPaginated(int $perPage = 15, array $columns = ['*'])
    {
        return new LengthAwarePaginator([], 0, $perPage);
    }
}
