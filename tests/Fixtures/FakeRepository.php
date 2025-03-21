<?php

namespace Kwidoo\Mere\Tests\Fixtures;

use Prettus\Repository\Eloquent\BaseRepository;

class FakeRepository extends BaseRepository
{
    public function model()
    {
        return FakeModel::class;
    }
}
