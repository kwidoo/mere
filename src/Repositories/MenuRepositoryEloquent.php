<?php

namespace Kwidoo\Mere\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Contracts\MenuRepository;
use Kwidoo\Mere\Models\MenuItem;
use App\Validators\MenuValidator;

/**
 * Class MenuRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MenuRepositoryEloquent extends BaseRepository implements MenuRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MenuItem::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
