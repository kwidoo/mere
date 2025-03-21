<?php

namespace Kwidoo\Mere\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class FakeModel extends Model
{
    protected $fillable = ['title', 'status'];
    protected $appends = ['computed'];

    public function getComputedAttribute(): string
    {
        return 'value';
    }
}
