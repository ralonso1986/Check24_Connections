<?php

namespace App\Connections\Infrastructure\Http\DataRepresenter;

use App\Connections\Application\DataRepresenter\DataRepresenter;
use FluidXml\FluidXml;

final class XMLDataRepresenter implements DataRepresenter
{
    private FluidXml $xml;

    public function __construct()
    {
        $this->xml = new FluidXml('');
    }

    public function represent(array $data): string
    {
        return $this->xml->addChild($data);
    }
}
