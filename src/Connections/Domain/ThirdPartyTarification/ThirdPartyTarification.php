<?php

declare(strict_types=1);

namespace App\Connections\Domain\ThirdPartyTarification;

use App\Connections\Domain\ThirdPartyEntity\ThirdPartyEntity;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\HolderVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\OccDriverVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\prevInsContDateVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\prevInsExistsVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\prevInsExpDateVO;

class ThirdPartyTarification
{
    public function __construct(
        private readonly HolderVO $holder,
        private readonly OccDriverVO $occDriver,
        private readonly prevInsExistsVO $prevInsExists,
        private readonly prevInsContDateVO $prevInsContDate,
        private readonly prevInsExpDateVO $prevInsExpDate,
        private readonly ThirdPartyEntity $mapping
    ) {
    }

    public function holder(): HolderVO
    {
        return $this->holder;
    }

    public function occDriver(): OccDriverVO
    {
        return $this->occDriver;
    }

    public function prevInsExists(): prevInsExistsVO
    {
        return $this->prevInsExists;
    }

    public function prevInsContDate(): prevInsContDateVO
    {
        return $this->prevInsContDate;
    }

    public function prevInsExpDate(): prevInsExpDateVO
    {
        return $this->prevInsExpDate;
    }

    /*public function thirdPartyMapping(): ThirdPartyMapping
    {
        return $this->mapping;
    }*/

    public function createThirdPartyRequest(): array
    {
        return $this->mapping->createThirdPartyRequest($this);
    }
}
