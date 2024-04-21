<?php

namespace App\Connections\Application\DataRepresenter;

interface DataRepresenter
{
    public function represent(array $data): string;
}
