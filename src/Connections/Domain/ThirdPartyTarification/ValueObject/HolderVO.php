<?php

declare(strict_types=1);

namespace App\Connections\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\Exception\EmptyInputDataException;

final class HolderVO
{
    public function __construct(private string $value)
    {
        if (!$value)
            throw new EmptyInputDataException('a not empty value for holder is required');
    }

    public function isHolderMainDriver(): bool
    {
        return strtoupper($this->value) === "CONDUCTOR_PRINCIPAL";
    }

    public function __toString()
    {
        return $this->value;
    }
}
