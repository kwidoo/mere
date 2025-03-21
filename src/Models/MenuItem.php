<?php

namespace Kwidoo\Mere\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Menu.
 *
 * @package namespace App\Models;
 */
class MenuItem extends Model implements Transformable
{
    use TransformableTrait;
    use NodeTrait;
    use HasFactory;

    protected static $factory = \Kwidoo\Mere\Database\Factories\MenuItemFactory::class;

    protected $fillable = [
        'path',
        'name',
        'component',
        'redirect',
        'props'
    ];

    protected $casts = [
        'redirect' => 'array',
        'props'    => 'array',
    ];
}
