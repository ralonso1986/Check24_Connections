<?php

namespace App\Tests\Unit\Domain\Input;

use App\Connections\Domain\ThirdPartyEntity\FooEntity;
use App\Connections\Domain\ThirdPartyTarification\ThirdPartyTarification;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\HolderVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\OccDriverVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsContDateVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExistsVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExpDateVO;
use PHPUnit\Framework\TestCase;

class ThirdPartyTarificationTest extends TestCase
{
     /**
     * @dataProvider youngDriverDataProvider
     */
    public function testThirdPartyTarification(
        $holder,
        $occDriver,
        $prevInsExists,
        $prevInsContDate,
        $prevInsExpDate,
        $expected
    ): void {
        $holderVO = new HolderVO($holder);
        $occDriverVO = new OccDriverVO($occDriver);
        $prevInsExistsVO = new PrevInsExistsVO($prevInsExists);
        $prevInsContrDateVO = new PrevInsContDateVO($prevInsContDate);
        $prevInsExpDateVO = new PrevInsExpDateVO($prevInsExpDate);
        $fooMapping = new FooEntity();

        $tPT = new ThirdPartyTarification(
            $holderVO,
            $occDriverVO,
            $prevInsExistsVO,
            $prevInsContrDateVO,
            $prevInsExpDateVO,
            $fooMapping
        );

        $this->assertInstanceOf(HolderVO::class, $tPT->holder());
        $this->assertInstanceOf(OccDriverVO::class, $tPT->occDriver());
        $this->assertInstanceOf(PrevInsExistsVO::class, $tPT->prevInsExists());
        $this->assertInstanceOf(PrevInsContDateVO::class, $tPT->prevInsContDate());
        $this->assertInstanceOf(PrevInsExpDateVO::class, $tPT->prevInsExpDate());
        //$this->assertInstanceOf(FooMapping::class, $tPT->thirdPartyMapping());

        $this->assertEquals($holder, (string) $tPT->holder());
        $this->assertEquals($occDriver, (string) $tPT->occDriver());
        $this->assertEquals($prevInsExists, (string) $tPT->prevInsExists());
        $this->assertEquals($prevInsContDate, (string) $tPT->prevInsContDate());
        $this->assertEquals($prevInsExpDate, (string) $tPT->prevInsExpDate());
        $this->assertEquals($expected, $tPT->createThirdPartyRequest());
    }

    public function youngDriverDataProvider(): \Generator
    {
        $json = json_decode(file_get_contents(__DIR__ . "/../../../../InputData/input.json"), true);

        $expectedYoungDriver["TarificacionThirdPartyRequest"]["Datos"] = [
            ...["DatosAseguradora" => ["SeguroEnVigor" => "NO"]],
            ...["DatosGenerales" => [
                "CondPpalEsTomador" => "YES",
                "ConductorUnico" => "YES",
                "FecCot" => date("Y-m-d\TH:i:s"),
                "AnosSegAnte" => 0,
                "NroCondOca" => 0
            ]]
        ];

        yield 'Young driver' => [
            $json[0]["holder"],
            $json[0]["occasionalDriver"],
            $json[0]["prevInsurance_exists"],
            $json[0]["prevInsurance_contractDate"],
            $json[0]["prevInsurance_expirationDate"],
            $expectedYoungDriver
        ];

        $expectedOldHolder["TarificacionThirdPartyRequest"]["Datos"] = [
            ...["DatosAseguradora" => ["SeguroEnVigor" => "NO"]],
            ...["DatosGenerales" => [
                "CondPpalEsTomador" => "YES",
                "ConductorUnico" => "NO",
                "FecCot" => date("Y-m-d\TH:i:s"),
                "AnosSegAnte" => 8,
                "NroCondOca" => 1
            ]]
        ];

        yield 'Old holder with second young driver' => [
            $json[1]["holder"],
            $json[1]["occasionalDriver"],
            $json[1]["prevInsurance_exists"],
            $json[1]["prevInsurance_contractDate"],
            $json[1]["prevInsurance_expirationDate"],
            $expectedOldHolder
        ];
    }
}
