<?php

namespace App\Domain\Serializers;

use League\Fractal\Serializer\ArraySerializer as FractalArraySerialized;

class ArraySerializer extends FractalArraySerialized
{
    public function collection($resourceKey, array $data)
    {
        return $data;
    }

    // public function item($resourceKey, array $data)
    // {
    //     if ($resourceKey === false) {
    //         return $data;
    //     }
    //     return array($resourceKey ?: 'data' => $data);
    // }
}