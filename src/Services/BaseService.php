<?php

namespace Kwidoo\Mere\Services;

use Kwidoo\Mere\Contracts\MenuService;
use Kwidoo\Mere\Presenters\ResourcePresenter;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Kwidoo\Mere\Data\ListQueryData;
use Kwidoo\Mere\Presenters\FormPresenter;
use Kwidoo\Mere\Contracts\Lifecycle;
use Kwidoo\Mere\Data\ShowQueryData;

/**
 * @property RepositoryInterface $repository
 */
abstract class BaseService implements \Kwidoo\Mere\Contracts\BaseService
{
    public function __construct(
        protected MenuService $menuService,
        protected RepositoryInterface $repository,
        protected Lifecycle $lifecycle,
    ) {}

    /**
     * @param ListQueryData $query
     *
     * @return mixed
     */
    public function list(ListQueryData $query)
    {
        $presenter = $query->presenter ?? $this->defaultListPresenter();
        $with = $query->with ?? [];

        $this->repository->setPresenter($presenter);

        return $this->lifecycle
            ->withoutEvents()
            ->withoutTrx()
            ->run(
                'viewAny',
                $this->eventKey(),
                $query,
                fn() => $query->perPage
                    ? $this->repository->with($with)->paginate($query->perPage, $query->columns)
                    : $this->repository->with($with)->all($query->columns)
            );
    }

    /**
     * Get a single lease agreement.
     *
     * @param ShowQueryData $query
     *
     * @return Model
     */
    public function getById(ShowQueryData $query): Model
    {
        $presenter = $query->presenter ?? $this->defaultFormPresenter();
        $with = $query->with ?? [];

        $this->repository->setPresenter($presenter);

        $model = $this->lifecycle
            ->withoutEvents()
            ->withoutTrx()
            ->run(
                'view',
                $this->eventKey(),
                [],
                fn() => $this->repository->with($with)->find($query->id)
            );

        if (!$model) {
            throw (new ModelNotFoundException())->setModel(get_class($this->repository->model()));
        }

        return $model;
    }

    /**
     * Create a new record.
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->lifecycle->run(
            'create',
            $this->eventKey(),
            $data,
            fn() => $this->handleCreate($data)
        );
    }

    /**
     * Update an existing record.
     *
     * @param  string  $id
     * @param  array   $data
     * @return mixed
     */
    public function update(string $id, array $data): mixed
    {
        return $this->lifecycle->run(
            'update',
            $this->eventKey(),
            $data,
            fn() => $this->handleUpdate($id, $data)
        );
    }

    /**
     * Delete a record.
     *
     * @param  string  $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        return $this->lifecycle->run(
            'delete',
            $this->eventKey(),
            [],
            fn() => $this->handleDelete($id)
        );
    }

    /**
     * Handle the creation of a new record.
     *
     * @param array $data
     * @return mixed
     */
    protected function handleCreate(array $data): mixed
    {
        $rules = $this->menuService->getRules(
            $this->studlyEvent('create')
        ) ?? [];

        $this->repository->setRules([
            'create' => $rules,
        ]);

        return $this->repository->create($data);
    }

    /**
     * Update an existing transaction.
     *
     * @param  string  $id
     * @param  array   $data
     * @return mixed
     */
    protected function handleUpdate(string $id, array $data): mixed
    {
        $rules = $this->menuService->getRules(
            $this->studlyEvent('update')
        ) ?? [];

        $this->repository->setRules([
            'update' => $rules,
        ]);
        return $this->repository->update($data, $id);
    }

    /**
     * Delete a transaction safely after validating lease status.
     *
     * @param  string  $id
     * @return bool
     */
    protected function handleDelete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    abstract protected function eventKey(): string;

    /**
     * @param string $suffix
     *
     * @return string
     */
    protected function studlyEvent(string $suffix): string
    {
        return Str::studly("{$this->eventKey()}-{$suffix}");
    }

    /**
     * @return string
     */
    protected function defaultListPresenter(): string
    {
        return ResourcePresenter::class;
    }

    /**
     * @return string
     */
    protected function defaultFormPresenter(): string
    {
        return FormPresenter::class;
    }
}
