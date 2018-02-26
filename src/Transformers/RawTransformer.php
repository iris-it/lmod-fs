<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 12/02/2018
 * Time: 14:07
 */

namespace Irisit\Filestash\Transformers;


use League\Fractal\TransformerAbstract;

class RawTransformer extends TransformerAbstract
{

    public function transform($data)
    {
        return $data;
    }
}