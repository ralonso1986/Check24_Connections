<?php

namespace App\Connections\Infrastructure\Http\DataRepresenter;

use App\Connections\Application\DataRepresenter\DataRepresenter;

final class JSONDataRepresenter implements DataRepresenter
{
    public function represent(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
