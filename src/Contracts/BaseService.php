<?php

namespace Kwidoo\Mere\Contracts;

interface BaseService
{
    /**
     * Get all properties.
     *
     * @return mixed
     */
    public function getAll(array $params = []);

    /**
     * @param int $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function getPaginated(int $perPage = 15, array $columns = ['*']);

    /**
     * Get a single property.
     *
     * @param  string  $id
     * @return mixed
     */
    public function getById(string $id);

    /**
     * Create a new property.
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing property.
     *
     * @param  string  $id
     * @param  array   $data
     * @return mixed
     */
    public function update(string $id, array $data);

    /**
     * Delete an existing property.
     *
     * @param  string  $id
     * @return bool
     */
    public function delete(string $id): bool;
}
