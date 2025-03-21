<?php

namespace Kwidoo\Mere\Repositories;

use Kwidoo\Mere\Validators\FormValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

abstract class RepositoryEloquent extends BaseRepository
{

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function validator()
    {
        return FormValidator::class;
    }

    public function setRules(array $rules): void
    {
        $this->validator->setRules($rules);
    }

    public function getErrors()
    {
        return $this->validator->errorsBag();
    }
}
