// File: ./config/config.php
<?php

/*
 * You can place your custom package configuration in here.
 */
return [

];

// File: ./src/Mere.php
<?php

namespace Kwidoo\Mere;

class Mere
{
    // Build your next great package.
}


// File: ./src/MereServiceProvider.php
<?php

namespace Kwidoo\Mere;

use Illuminate\Support\ServiceProvider;
use Kwidoo\Mere\Contracts\MenuRepository;
use Kwidoo\Mere\Contracts\MenuService as MenuServiceContract;
use Kwidoo\Mere\Repositories\MenuRepositoryEloquent;
use Kwidoo\Mere\Services\MenuService;

class MereServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mere');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'mere');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('mere.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/mere'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/mere'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/mere'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'mere');


        $this->app->bind(MenuServiceContract::class, MenuService::class);
        $this->app->bind(MenuRepository::class, MenuRepositoryEloquent::class);


        // Register the main class to use with the facade
        $this->app->singleton('mere', function () {
            return new Mere;
        });
    }
}


// File: ./src/Contracts/MenuService.php
<?php

namespace Kwidoo\Mere\Contracts;

interface MenuService
{
    public function getMenus();

    public function getFields(string $name);

    public function getRules(string $name);
}


// File: ./src/Contracts/BaseService.php
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


// File: ./src/Contracts/MenuRepository.php
<?php

namespace Kwidoo\Mere\Contracts;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface MenuRepository.
 *
 * @package namespace App\Contracts;
 */
interface MenuRepository extends RepositoryInterface
{
    //
}


// File: ./src/Repositories/MenuRepositoryEloquent.php
<?php

namespace Kwidoo\Mere\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Kwidoo\Mere\Models\MenuItem;
use App\Validators\MenuValidator;
use Kwidoo\Mere\Contracts\MenuRepository;

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


// File: ./src/Repositories/RepositoryEloquent.php
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


// File: ./src/Models/MenuItem.php
<?php

namespace Kwidoo\Mere\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Menu.
 *
 * @package namespace App\Models;
 */
class MenuItem extends Model implements Transformable
{
    use TransformableTrait;
    use NodeTrait;

    protected $fillable = [
        'path',
        'name',
        'component',
        'redirect',
        'props'
    ];

    protected $casts = [
        'redirect' => 'array',
        'props'    => 'array',
    ];
}


// File: ./src/Presenters/ResourcePresenter.php
<?php

namespace Kwidoo\Mere\Presenters;

use Kwidoo\Mere\Transformers\ResourceTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PropertyPresenter.
 *
 * @package namespace App\Presenters;
 */
class ResourcePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return app()->make(ResourceTransformer::class);
    }
}


// File: ./src/Presenters/FormPresenter.php
<?php

namespace Kwidoo\Mere\Presenters;

use Kwidoo\Mere\Transformers\FormTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PropertyPresenter.
 *
 * @package namespace Kwidoo\Mere\Presenters;
 */
class FormPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new FormTransformer();
    }
}


// File: ./src/Transformers/FormTransformer.php
<?php

namespace Kwidoo\Mere\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Property;

/**
 * Class FormTransformer.
 *
 * @package namespace App\Transformers;
 */
class FormTransformer extends TransformerAbstract
{
    /**
     * Transform the Property entity.
     *
     * @param \App\Models\Property $model
     *
     * @return array
     */
    public function transform(Property $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}


// File: ./src/Transformers/ResourceTransformer.php
<?php

namespace Kwidoo\Mere\Transformers;

use League\Fractal\TransformerAbstract;
use Kwidoo\Mere\Contracts\MenuService;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

/**
 * Class TenantTransformer.
 *
 * @package namespace App\Transformers;
 */
class ResourceTransformer extends TransformerAbstract
{
    public function __construct(protected MenuService $menuService) {}
    /**
     * Transform the Model entity.
     *
     * @param Model $model
     *
     * @return array
     */
    public function transform(Model $model)
    {
        $class = (new ReflectionClass($model))->getShortName();
        $fields = $this->menuService->getFields($class . 'List') ?? [];

        $transformed = [];
        foreach ($fields as $field) {
            $transformed[$field['key']] = $model->{$field['key']};
        }

        return $transformed;
    }
}


// File: ./src/Http/Resources/ResourceCollection.php
<?php

namespace Kwidoo\Mere\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;

class ResourceCollection extends BaseResourceCollection
{
    protected array $fields = [];
    protected string $label = 'resource';
    protected array $actions = [
        'create' => true,
        'edit' => true,
        'delete' => true,
    ];
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...$this->collection,
            'resource' => [
                'label' => $this->label,
                'fields' => $this->fields,
                'actions' => $this->actions,
            ],
        ];
    }

    public function fields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function canCreate(bool $create): self
    {
        $this->actions['create'] = $create;

        return $this;
    }

    public function canEdit(bool $edit): self
    {
        $this->actions['edit'] = $edit;

        return $this;
    }

    public function canDelete(bool $delete): self
    {
        $this->actions['delete'] = $delete;

        return $this;
    }
}


// File: ./src/Http/Resources/FormResource.php
<?php

namespace Kwidoo\Mere\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}


// File: ./src/Http/Requests/BaseRequest.php
<?php

namespace Kwidoo\Mere\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Kwidoo\Mere\Contracts\MenuService;
use Illuminate\Support\Str;

class BaseRequest extends FormRequest
{
    public function __construct(protected MenuService $menuService, array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = 'Create';
        if ($this->method() === 'PUT') {
            $type = 'Update';
        }
        return $this
            ->menuService
            ->getRules(
                Str::studly(
                    Str::singular(
                        $this->segment(2)
                    ) . $type
                )
            );
    }
}


// File: ./src/Http/Controllers/MenuController.php
<?php

namespace Kwidoo\Mere\Http\Controllers;

use Kwidoo\Mere\Contracts\MenuService;

class MenuController
{
    public function __construct(protected MenuService $service) {}

    public function __invoke()
    {
        return $this->service->getMenus();
    }
}


// File: ./src/Http/Controllers/ResourceController.php
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


// File: ./src/MereFacade.php
<?php

namespace Kwidoo\Mere;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kwidoo\Mere\Skeleton\SkeletonClass
 */
class MereFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mere';
    }
}


// File: ./src/routes.php
<?php

use Illuminate\Support\Facades\Route;
use Kwidoo\Mere\Http\Controllers\MenuController;

Route::middleware('auth:api')->group(function () {
    Route::get('api/menu', MenuController::class);
});


// File: ./src/Validators/FormValidator.php
<?php

namespace Kwidoo\Mere\Validators;

use Kwidoo\Mere\Contracts\MenuService;
use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Str;


class FormValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}


// File: ./src/Services/MenuService.php
<?php

namespace Kwidoo\Mere\Services;

use Kwidoo\Mere\Contracts\MenuRepository;
use Kwidoo\Mere\Contracts\MenuService as MenuServiceContract;
use Kwidoo\Mere\Models\MenuItem;

class MenuService implements MenuServiceContract
{
    public function __construct(protected MenuRepository $menuRepository) {}

    public function getMenus()
    {
        return MenuItem::all();
    }

    public function getFields(string $name)
    {
        $menuItem = MenuItem::where('name', $name)->first();
        if ($menuItem) {
            return $menuItem->props['fields'];
        }
    }

    public function getRules(string $name)
    {
        $menuItem = MenuItem::where('name', $name)->first();
        if ($menuItem) {
            return $menuItem->props['rules'];
        }
    }
}


// File: ./src/Services/BaseService.php
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


