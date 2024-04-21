<?php

namespace App\Connections\Application\CreateThirdPartyRequest;

use App\Connections\Application\CreateThirdPartyRequest\CreateThirdPartyRequestResponse;
use App\Connections\Domain\ThirdPartyTarification\ThirdPartyTarification;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\HolderVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\OccDriverVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsContDateVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExistsVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExpDateVO;
use App\Connections\Domain\ThirdPartyEntity\FooEntity;

class CreateThirdPartyRequest
{
    public function __invoke(array $inputParams): CreateThirdPartyRequestResponse
    {
        $holderVO = new HolderVO($inputParams["holder"]);
        $occDriverVO = new OccDriverVO($inputParams["occDriver"]);
        $prevInsExistsVO = new PrevInsExistsVO($inputParams["prevInsExists"]);
        $prevInsContrDateVO = new PrevInsContDateVO($inputParams["prevInsContrDate"]);
        $prevInsExpDateVO = new PrevInsExpDateVO($inputParams["prevInsExpDate"]);

        $thirdPartyTarification = new ThirdPartyTarification(
            $holderVO,
            $occDriverVO,
            $prevInsExistsVO,
            $prevInsContrDateVO,
            $prevInsExpDateVO,
            new FooEntity()
        );

        return new CreateThirdPartyRequestResponse(
            $thirdPartyTarification->createThirdPartyRequest()
        );
    }
}
