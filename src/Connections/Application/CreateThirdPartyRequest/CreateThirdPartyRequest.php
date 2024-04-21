<?php

namespace App\Connections\Application\CreateThirdPartyRequest;

use App\Connections\Application\CreateThirdPartyRequest\CreateThirdPartyRequestResponse;
use App\Connections\Domain\ThirdPartyTarification\ThirdPartyTarification;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\HolderVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\OccDriverVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExistsVO;
use App\Connections\Domain\ThirdPartyEntity\FooEntity;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsContDateExpDateVO;

class CreateThirdPartyRequest
{
    public function __invoke(array $inputParams): CreateThirdPartyRequestResponse
    {
        $holderVO = new HolderVO($inputParams["holder"]);
        $occDriverVO = new OccDriverVO($inputParams["occDriver"]);
        $prevInsExistsVO = new PrevInsExistsVO($inputParams["prevInsExists"]);
        $prevInsContDateExpDateVO = new PrevInsContDateExpDateVO(
            $inputParams["prevInsContrDate"],
            $inputParams["prevInsExpDate"]
        );

        $thirdPartyTarification = new ThirdPartyTarification(
            $holderVO,
            $occDriverVO,
            $prevInsExistsVO,
            $prevInsContDateExpDateVO,
            new FooEntity()
        );

        return new CreateThirdPartyRequestResponse(
            $thirdPartyTarification->createThirdPartyRequest()
        );
    }
}
