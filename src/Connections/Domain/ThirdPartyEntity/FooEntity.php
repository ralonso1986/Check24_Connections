<?php

declare(strict_types=1);

namespace App\Connections\Domain\ThirdPartyEntity;

use App\Connections\Domain\ThirdPartyTarification\ThirdPartyTarification;
use App\Connections\Domain\Exception\WrongInputDataException;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\HolderVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\OccDriverVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExistsVO;

class FooEntity implements ThirdPartyEntity
{
    public function createThirdPartyRequest(ThirdPartyTarification $tPT): array
    {
        $holder = $tPT->holder();
        $occDriver = $tPT->occDriver();
        $prevInsExists = $tPT->prevInsExists();
        [$prevInsContDate, $prevInsExpDate] = explode(",",(string) $tPT->prevInsContDateExpDate());

        $tPTData = ["DatosAseguradora" => [
            "SeguroEnVigor" => $this->prevInsInForce($prevInsExists, $prevInsExpDate)
        ]];
        
        $tPTGeneralData = ["DatosGenerales" => [
            "CondPpalEsTomador" => $this->mainDriverIsHolder($holder),
            "ConductorUnico" => $this->holderIsUniqueDriver($holder, $occDriver),
            "FecCot" => $this->dateCot(),
            "AnosSegAnte" => $this->prevInsYears($prevInsExists, $prevInsContDate, $prevInsExpDate),
            "NroCondOca" => $this->numAddiDrivers($holder, $occDriver)
        ]];

        $tPTRequest["TarificacionThirdPartyRequest"]["Datos"] = [
            ...$tPTData,
            ...$tPTGeneralData
        ];

        return $tPTRequest;
    }

    public function mainDriverIsHolder(HolderVO $holder): string
    {
        return $holder->isHolderMainDriver() ? "YES" : "NO";
    }

    public function holderIsUniqueDriver(HolderVO $holder, OccDriverVO $occDriver): string
    {
        return $holder->isHolderMainDriver() && !$occDriver->isOccDriver() ? 'YES' : 'NO';
    }

    public function dateCot(): string
    {
        return date("Y-m-d\TH:i:s", strtotime("now"));
    }

    public function prevInsYears(
        PrevInsExistsVO $prevInsExists,
        string $prevInsContDate,
        string $prevInsExpDate
    ): int {
        $prevInsExists = $prevInsExists->prevInsExists();

        if ($prevInsExists && (!$prevInsContDate || !$prevInsExpDate))
            throw new WrongInputDataException("Old insurance contract date or expiration date are missing from input data");

        if ($prevInsExists)
            return (int) date('Y', strtotime($prevInsExpDate))
                - (int) date('Y', strtotime($prevInsContDate));
        else
            return 0;
    }

    public function numAddiDrivers(HolderVO $holder, OccDriverVO $occDriver): int
    {
        $addiDrivers = 0;

        if (!$holder->isHolderMainDriver())
            $addiDrivers += 1;
        if ($occDriver->isOccDriver())
            $addiDrivers += 1;

        return $addiDrivers;
    }

    public function prevInsInForce(PrevInsExistsVO $prevInsExists, string $prevInsExpDate): string
    {
        $prevInsExistsBool = $prevInsExists->prevInsExists();

        if ($prevInsExistsBool && !$prevInsExpDate)
            throw new WrongInputDataException("Old insurance expiration date is missing from input data");

        if (!$prevInsExistsBool)
            return "NO";
        else
            return strtotime("now") - strtotime($prevInsExpDate) < 0 ? "YES" : "NO";
    }
}
