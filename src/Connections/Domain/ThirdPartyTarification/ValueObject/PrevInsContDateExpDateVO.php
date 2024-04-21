<?php

declare(strict_types=1);

namespace App\Connections\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\Exception\WrongInputDataException;

use DateTime;

final class PrevInsContDateExpDateVO
{
    public function __construct(private string $contDate, private string $expDate)
    {
        if ($contDate && !$this->validateDate($contDate, 'Y-m-d')) {
            throw new WrongInputDataException('a date in Y-m-d format is required for prev insurance contract date');
        }

        if ($expDate && !$this->validateDate($expDate, 'Y-m-d')) {
            throw new WrongInputDataException('a date in Y-m-d format is required for prev insurance expiration date');
        }

        if ($contDate && $expDate && strtotime($contDate) > strtotime($expDate))
            throw new WrongInputDataException('prev insurance contract date must be older than prev insurance expiration date');
    }

    public function __toString()
    {
        return $this->contDate . "," . $this->expDate;
    }

    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return !$date || ($d && $d->format($format) == $date);
    }
}
