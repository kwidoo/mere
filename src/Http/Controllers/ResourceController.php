<?php

namespace Kwidoo\Mere\Http\Controllers;

use Kwidoo\Mere\Contracts\BaseService;
use Kwidoo\Mere\Http\Requests\BaseRequest;
use Kwidoo\Mere\Http\Resources\ResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Kwidoo\Mere\Data\ListQueryData;
use Kwidoo\Mere\Data\ShowQueryData;
use Kwidoo\Mere\Factories\ServiceFactory;
use Kwidoo\Mere\Http\Resources\FormResource;

/**
 * @property BaseService $service
 */
class ResourceController extends Controller
{
    public function __construct(protected ServiceFactory $factory) {}

    /**
     * Display a listing of lease agreements.
     *
     * @return ResourceCollection
     */
    public function index(ListQueryData $data): ResourceCollection
    {
        $service = $this->factory->make($data->resource);
        $response = $service->list($data);

        return new ResourceCollection($response);
    }

    /**
     * @param string $id
     *
     * @return JsonResource
     */
    public function show(string $resource, string $id) //: JsonResource
    {
        $service = $this->factory->make($resource);

        return new FormResource($service->getById(new ShowQueryData($id, $resource)));
    }

    /**
     * @return JsonResource
     */
    public function store(BaseRequest $request, string $resource): JsonResource
    {
        $service = $this->factory->make($resource);

        $response = $service->create($request->validated());

        return new FormResource($response);
    }

    /**
     * @param string $id
     *
     * @return JsonResource
     */
    public function update(BaseRequest $request, string $resource, string $id): JsonResource
    {
        $service = $this->factory->make($resource);

        $response = $service->update($id, $request->validated());

        return new FormResource($response);
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     */
    public function destroy(string $resource, string $id): JsonResponse
    {
        $service = $this->factory->make($resource);

        $response = $service->delete($id);

        return response()->json(['deleted' => $response]);
    }
}
