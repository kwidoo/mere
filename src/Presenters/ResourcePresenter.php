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
