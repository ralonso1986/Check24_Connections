<?php

declare(strict_types=1);

namespace App\Connections\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\Exception\WrongInputDataException;

use DateTime;

final class PrevInsContDateVO
{
    public function __construct(private string $value)
    {
        if ($value && !$this->validateDate($value, 'Y-m-d')) {
            throw new WrongInputDataException('a date in Y-m-d format is required for "prev insurance contract date"');
        }
    }

    public function __toString()
    {
        return $this->value;
    }

    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return !$date || ($d && $d->format($format) == $date);
    }
}
