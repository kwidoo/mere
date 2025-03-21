<?php

namespace Kwidoo\Mere;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kwidoo\Mere\Skeleton\SkeletonClass
 */
class MereFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mere';
    }
}
