<?php

declare(strict_types=1);

namespace App\Connections\Domain\ThirdPartyEntity;

use App\Connections\Domain\ThirdPartyTarification\ThirdPartyTarification;
use App\Connections\Domain\Exception\WrongInputDataException;

class FooEntity implements ThirdPartyEntity
{
    /*private string $mainDriverIsHolder;
    private string $holderIsUniqueDriver;
    private string $dateCot;
    private int $prevInsYears;
    private int $numAddiDrivers;
    private $prevInsInForce;*/

    public function createThirdPartyRequest(ThirdPartyTarification $tPT): array
    {
        $holder = (string) $tPT->holder();
        $occDriver = (string) $tPT->occDriver();
        $prevInsExists = (string) $tPT->prevInsExists();
        $prevInsContDate = (string) $tPT->prevInsContDate();
        $prevInsExpDate = (string) $tPT->prevInsExpDate();

        /*$this->mainDriverIsHolder = $this->mainDriverIsHolder($holder);
        $this->holderIsUniqueDriver = $this->holderIsUniqueDriver($holder, $occDriver);
        $this->dateCot = $this->dateCot();
        $this->prevInsYears = $this->prevInsYears($prevInsExists, $prevInsContDate, $prevInsExpDate);
        $this->numAddiDrivers = $this->numAddiDrivers($holder, $occDriver);
        $this->prevInsInForce = $this->prevInsInForce($prevInsExists, $prevInsExpDate);*/

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

    public function mainDriverIsHolder($holder): string
    {
        return $holder === "CONDUCTOR_PRINCIPAL" ? "YES" : "NO";
    }

    public function holderIsUniqueDriver($holder, $occDriver): string
    {
        return $holder === "CONDUCTOR_PRINCIPAL" && $occDriver === 'NO' ? 'YES' : 'NO';
    }

    public function dateCot(): string
    {
        return date("Y-m-d\TH:i:s", strtotime("now"));
    }

    public function prevInsYears(
        $prevInsExists,
        $prevInsContDate,
        $prevInsExpDate
    ): int {
        if ($prevInsExists === "SI" && (!$prevInsContDate || !$prevInsExpDate))
            throw new WrongInputDataException("Old insurance contract date or expiration date are missing from input data");

        if ($prevInsExists === "SI" && (strtotime($prevInsContDate) > strtotime($prevInsExpDate)))
            throw new WrongInputDataException("Old insurance contract date is newer than expiration date from input data");

        if ($prevInsExists === "SI")
            return (int) date('Y', strtotime($prevInsExpDate))
                - (int) date('Y', strtotime($prevInsContDate));
        else
            return 0;
    }

    public function numAddiDrivers($holder, $occDriver): int
    {
        $addiDrivers = 0;

        if ($holder !== "CONDUCTOR_PRINCIPAL")
            $addiDrivers += 1;
        if ($occDriver === "SI")
            $addiDrivers += 1;

        return $addiDrivers;
    }

    public function prevInsInForce($prevInsExists, $prevInsExpDate): string
    {
        if ($prevInsExists === "SI" && !$prevInsExpDate)
            throw new WrongInputDataException("Old insurance expiration date is missing from input data");

        if ($prevInsExists === "NO")
            return "NO";
        else
            return strtotime("now") - strtotime($prevInsExpDate) < 0 ? "YES" : "NO";
    }
}
