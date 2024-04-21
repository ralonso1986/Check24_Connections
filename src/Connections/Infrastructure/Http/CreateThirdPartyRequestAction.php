<?php

declare(strict_types=1);

namespace App\Connections\Infrastructure\Http;

use App\Connections\Application\CreateThirdPartyRequest\CreateThirdPartyRequest;
use App\Connections\Domain\Exception\EmptyInputDataException;
use App\Connections\Domain\Exception\MissingInputDataException;
use App\Connections\Domain\Exception\WrongInputDataException;
use App\Connections\Infrastructure\Http\DataRepresenter\XMLDataRepresenter;
use App\Connections\Infrastructure\Http\DataRepresenter\JSONDataRepresenter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Throwable;

class CreateThirdPartyRequestAction
{
    public function __construct(
        private CreateThirdPartyRequestActionResponder $responder,
        private XMLDataRepresenter $dataRepresenter
    ) {
    }

    public function __invoke(Request $request): Response
    {
        try {
            $inputParams = json_decode($request->getContent(), true);
            $filteredInputParams = [
                "holder" => $inputParams["holder"],
                "occDriver" => $inputParams["occasionalDriver"],
                "prevInsExists" => $inputParams["prevInsurance_exists"],
                "prevInsContrDate" => $inputParams["prevInsurance_contractDate"],
                "prevInsExpDate" => $inputParams["prevInsurance_expirationDate"]
            ];

            $tPReqResponse = (new CreateThirdPartyRequest())($filteredInputParams);

            $tPReqXML = $this->dataRepresenter->represent($tPReqResponse->thirdPartyReq());

            $this->responder->loadThirdPartyReq($tPReqXML);
        } catch (MissingInputDataException $e) {
            $this->responder->loadError("Missing input data (" . $e->getMessage() . ")");
        } catch (EmptyInputDataException $e) {
            $this->responder->loadError("Empty input data (" . $e->getMessage() . ")");
        } catch (WrongInputDataException $e) {
            $this->responder->loadError("Wrong input data (" . $e->getMessage() . ")");
        } catch (Throwable $e) {
            $this->responder->loadError("Not handled error or exception (" . $e->getMessage() . ")");
        }

        return $this->responder->response();
    }
}
