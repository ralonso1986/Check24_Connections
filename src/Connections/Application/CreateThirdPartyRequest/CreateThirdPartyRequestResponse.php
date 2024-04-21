<?php

declare(strict_types=1);

namespace App\Connections\Application\CreateThirdPartyRequest;

class CreateThirdPartyRequestResponse
{
    public function __construct(private readonly array $data)
    {
    }

    public function thirdPartyReq() : array
    {
        return $this->data;
    }
}