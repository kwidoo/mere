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
