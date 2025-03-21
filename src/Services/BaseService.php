<?php

namespace Kwidoo\Mere\Services;

use Exception;
use Kwidoo\Mere\Contracts\MenuService;
use Kwidoo\Mere\Presenters\ResourcePresenter;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @property RepositoryInterface $repository
 */
abstract class BaseService
{
    public function __construct(protected MenuService $menuService) {}
    /**
     * Get all lease agreements.
     *
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\LeaseAgreement>
     */
    public function getAll(array $params = [])
    {
        if (array_key_exists('columns', $params)) {
            $columns = $params['columns'];
        }
        $this->repository->setPresenter(ResourcePresenter::class);

        return $this->repository->all($columns ?? ['*']);
    }

    /**
     * @param int $perPage
     *
     * @return mixed
     */
    public function getPaginated(int $perPage = 15, array $columns = ['*'])
    {
        $this->repository->setPresenter(ResourcePresenter::class);

        return $this->repository->paginate($perPage, $columns);
    }

    /**
     * Get a single lease agreement.
     *
     * @param string $id
     *
     * @return Model
     */
    public function getById(string $id)
    {
        $this->repository->setPresenter('Kwidoo\Mere\Presenters\FormPresenter');

        $model = $this->repository->find($id);

        if (!$model) {
            throw new Exception('Resource not found');
        }

        return $model;
    }

    /**
     * Create a new transaction.
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data)
    {
        event('before.' . $this->eventKey() . '.created', $data);

        $rules = $this->menuService->getRules(
            Str::studly(
                implode('-', [$this->eventKey(), 'create'])
            )
        );

        try {
            $this->repository->setRules([
                'create' => $rules,
            ]);

            $transaction = $this->repository->create($data);
        } catch (Exception $e) {
            throw ValidationException::withMessages($this->repository->getErrors()->messages())
                ->errorBag('default')
                ->redirectTo(url()->previous());
        }

        event('after.' . $this->eventKey() . '.created', $transaction);

        return $transaction;
    }

    /**
     * Update an existing transaction.
     *
     * @param  string  $id
     * @param  array   $data
     * @return mixed
     */
    public function update(string $id, array $data)
    {
        event('before.' . $this->eventKey() . '.updated', [$id, $data]);

        $rules = $this->menuService->getRules(
            Str::studly(
                implode('-', [$this->eventKey(), 'update'])
            )
        );
        try {
            $this->repository->setRules([
                'update' => $rules,
            ]);
            $transaction = $this->repository->update($data, $id);
        } catch (Exception $e) {
            throw ValidationException::withMessages($this->repository->getErrors()->messages())
                ->errorBag('default')
                ->redirectTo(url()->previous());
        }

        event('after.' . $this->eventKey() . '.updated', $transaction);

        return $transaction;
    }

    /**
     * Delete a transaction safely after validating lease status.
     *
     * @param  string  $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        event('before.' . $this->eventKey() . '.deleted', $id);
        $deleted = $this->repository->delete($id);

        event('after.' . $this->eventKey() . '.deleted', [$id, $deleted]);

        return $deleted;
    }

    abstract protected function eventKey(): string;
}
