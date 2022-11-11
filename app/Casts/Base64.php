<?php

namespace App\Casts;

class Base64 implements \Illuminate\Contracts\Database\Eloquent\CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param string $key
     * @param  mixed  $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return base64_decode($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
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
        return preg_replace('/=+$/', '', base64_encode($value));
    }
}
