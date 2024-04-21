<?php

namespace App\Connections\Domain\ThirdPartyEntity;

use App\Connections\Domain\ThirdPartyTarification\ThirdPartyTarification;

interface ThirdPartyEntity {
    public function createThirdPartyRequest(ThirdPartyTarification $tPT): array;
}