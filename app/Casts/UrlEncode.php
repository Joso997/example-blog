<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class UrlEncode implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  Model  $model
     * @param string $key
     * @param  mixed  $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     * @param string $key
     * @param  array  $value
     * @param array $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $this::encode($value);
    }

    public static function encode($value){
        return rawurlencode(base64_encode($value));
    }
}
