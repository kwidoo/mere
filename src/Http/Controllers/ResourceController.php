<?php

namespace Kwidoo\Mere\Http\Controllers;

use Exception;
use Kwidoo\Mere\Contracts\BaseService;
use Kwidoo\Mere\Http\Requests\BaseRequest;
use Kwidoo\Mere\Http\Resources\ResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Kwidoo\Mere\Http\Resources\FormResource;

/**
 * @property BaseService $service
 */
class ResourceController extends Controller
{
    /**
     * @var string
     */
    protected string $storeRequest;

    /**
     * @var string
     */
    protected string $updateRequest;

    protected BaseService $service;

    public function __construct(Request $request)
    {
        $resource = $request->segment(2);
        $resourceMap = app()->make('resource.map');

        if (isset($resourceMap[$resource])) {
            $this->service = app()->make($resourceMap[$resource]);
        }

        if (!app()->runningInConsole() && !isset($this->service)) {
            throw new Exception('Service not set for the resource.');
        }
    }

    /**
     * Display a listing of lease agreements.
     *
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $response = $this->service->getPaginated();

        return new ResourceCollection($response);
    }

    /**
     * @param string $id
     *
     * @return JsonResource
     */
    public function show(string $id) //: JsonResource
    {
        return new FormResource($this->service->getById($id));
    }

    /**
     * @return JsonResource
     */
    public function store(BaseRequest $request): JsonResource
    {
        $response = $this->service->create($request->validated());

        return new FormResource($response);
    }

    /**
     * @param string $id
     *
     * @return JsonResource
     */
    public function update(BaseRequest $request, string $id): JsonResource
    {
        $response = $this->service->update($id, $request->validated());

        return new FormResource($response);;
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $response = $this->service->delete($id);

        return response()->json(['deleted' => $response]);
    }
}
