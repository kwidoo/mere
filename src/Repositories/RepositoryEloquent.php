<?php

namespace Kwidoo\Mere\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Kwidoo\Mere\Presenters\ResourcePresenter;
use Prettus\Repository\Criteria\RequestCriteria;

abstract class RepositoryEloquent extends BaseRepository
{
    public function presenter()
    {
        return ResourcePresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
