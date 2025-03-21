<?php

namespace Kwidoo\Mere\Services;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * @property RepositoryInterface $repository
 */
abstract class BaseService
{
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
        return $this->repository->all($columns ?? ['*']);
    }

    /**
     * @param int $perPage
     *
     * @return mixed
     */
    public function getPaginated(int $perPage = 15, array $columns = ['*'])
    {
        return $this->repository->paginate($perPage, $columns);
    }

    /**
     * Get a single lease agreement.
     *
     * @param string $id
     *
     * @return \App\Models\LeaseAgreement
     */
    public function getById(string $id)
    {
        return $this->repository->findOrFail($id);
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

        $transaction = $this->repository->create($data);

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

        $transaction = $this->repository->update($data, $id);

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
