<?php

declare(strict_types=1);

namespace App\Connections\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\Exception\WrongInputDataException;

final class PrevInsExistsVO
{
    private string $value;

    private const ALLOWED_VALUES = ["SI", "NO"];

    public function __construct(string $value)
    {
        if (!in_array(strtoupper($value), self::ALLOWED_VALUES))
            throw new WrongInputDataException('"prev insurance exists" is required to be "SI" or "NO"');

        $this->value = strtoupper($value);
    }

    public function __toString()
    {
        return $this->value;
    }
}
