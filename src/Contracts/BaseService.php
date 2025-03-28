<?php

namespace Kwidoo\Mere\Contracts;

use Illuminate\Database\Eloquent\Model;
use Kwidoo\Mere\Data\ListQueryData;
use Kwidoo\Mere\Data\ShowQueryData;

interface BaseService
{
    /**
     * Get all properties.
     *
     * @return mixed
     */
    public function list(ListQueryData $data);

    /**
     * Get a single property.
     *
     * @param  string  $id
     * @return Model
     */
    public function getById(ShowQueryData $query): Model;

    /**
     * Create a new property.
     *
     * @param  array  $data
     * @return mixed
     */
    public function create(array $data): mixed;

    /**
     * Update an existing property.
     *
     * @param  string  $id
     * @param  array   $data
     * @return mixed
     */
    public function update(string $id, array $data): mixed;

    /**
     * Delete an existing property.
     *
     * @param  string  $id
     * @return bool
     */
    public function delete(string $id): bool;
}
