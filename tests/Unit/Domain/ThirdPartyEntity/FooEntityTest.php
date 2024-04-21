<?php

namespace App\Tests\Unit\Domain\ThirdPartyEntity;

use App\Connections\Domain\Exception\WrongInputDataException;
use App\Connections\Domain\ThirdPartyEntity\FooEntity;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\HolderVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\OccDriverVO;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExistsVO;
use PHPUnit\Framework\TestCase;

class FooEntityTest extends TestCase
{
    private FooEntity $fooEntity;
    protected function setUp(): void
    {
        $this->fooEntity = new fooEntity();
    }
    /**
     * @dataProvider mainDriverIsHolderDataProvider
     */
    public function testMainDriverIsHolder($holder, $expected): void
    {
        $this->assertEquals($expected, $this->fooEntity->mainDriverIsHolder(holder: $holder));
    }

    public function mainDriverIsHolderDataProvider(): \Generator
    {
        yield 'Conductor principal' => [new HolderVO("CONDUCTOR_PRINCIPAL"), "YES"];
        yield 'No es conductor principal' => [new HolderVO("PRIMO_CONDUCTOR"), "NO"];
    }

    /**
     * @dataProvider holderIsUniqueDriverDataProvider   
     */
    public function testHolderIsUniqueDriver($holder, $occDriver, $expected): void
    {
        $this->assertEquals($expected, $this->fooEntity->holderIsUniqueDriver(
            holder: $holder,
            occDriver: $occDriver
        ));
    }

    public function holderIsUniqueDriverDataProvider(): \Generator
    {
        yield 'Conductor único' => [new HolderVO("CONDUCTOR_PRINCIPAL"), new OccDriverVO("NO"), "YES"];
        yield 'No es conductor único caso 1' => [new HolderVO("CONDUCTOR_PRINCIPAL"), new OccDriverVO("SI"), "NO"];
        yield 'No es conductor único caso 2' => [new HolderVO("PRIMO_PRINCIPAL"), new OccDriverVO("NO"), "NO"];
        yield 'No es conductor único caso 3' => [new HolderVO("PRIMO_PRINCIPAL"), new OccDriverVO("SI"), "NO"];
    }

    public function testDateCot(): void
    {
        $this->assertEquals(date("Y-m-d\TH:i:s", strtotime('now')), $this->fooEntity->dateCot());
    }

    /**
     * @dataProvider prevInsYearsDataProvider   
     */
    public function testPrevInsYears(
        $prevInsExists,
        $prevInsContDate,
        $prevInsExpDate,
        $expected
    ): void {
        if ($expected === WrongInputDataException::class) {
            $this->expectException($expected);
            $this->fooEntity->prevInsYears(
                prevInsExists: $prevInsExists,
                prevInsContDate: $prevInsContDate,
                prevInsExpDate: $prevInsExpDate
            );
        } else {
            $this->assertEquals($expected, $this->fooEntity->prevInsYears(
                prevInsExists: $prevInsExists,
                prevInsContDate: $prevInsContDate,
                prevInsExpDate: $prevInsExpDate
            ));
        }
    }

    public function prevInsYearsDataProvider(): \Generator
    {
        yield 'Sin seguro anterior' => [new PrevInsExistsVO("NO"), "", "", "0"];
        yield 'Con seguro anterior pero sin contract date' => [
            new PrevInsExistsVO("SI"), "", "2024-04-30",
            WrongInputDataException::class
        ];
        yield 'Con seguro anterior pero sin expiration date' => [
            new PrevInsExistsVO("SI"), "2024-04-30", "",
            WrongInputDataException::class
        ];
        yield 'Con seguro anterior y fechas correctas' => [new PrevInsExistsVO("SI"), "2020-04-30", "2024-04-30", "4"];
    }

    /**
     * @dataProvider numAddiDriversDataProvider   
     */
    public function testNumAddiDrivers($holder, $occDriver, $expected): void
    {
        $this->assertEquals($expected, $this->fooEntity->numAddiDrivers(
            holder: $holder,
            occDriver: $occDriver
        ));
    }

    public function numAddiDriversDataProvider(): \Generator
    {
        yield '0 conductores ocasionales' => [new HolderVO("CONDUCTOR_PRINCIPAL"), new OccDriverVO("NO"), "0"];
        yield '1 conductor ocasional caso 1' => [new HolderVO("CONDUCTOR_PRINCIPAL"), new OccDriverVO("SI"), "1"];
        yield '1 conductor ocasional caso 2' => [new HolderVO("PRIMO DEL CONDUCTOR PRINCIPAL"), new OccDriverVO("NO"), "1"];
        yield '2 conductores ocasionales' => [new HolderVO("PRIMO DEL CONDUCTOR PRINCIPAL"), new OccDriverVO("SI"), "2"];
    }

    /**
     * @dataProvider prevInsInForceDataProvider   
     */
    public function testPrevInsInForce(
        $prevInsExists,
        $prevInsExpDate,
        $expected
    ): void {

        if ($expected === WrongInputDataException::class) {
            $this->expectException($expected);
            $this->fooEntity->prevInsInForce(
                prevInsExists: $prevInsExists,
                prevInsExpDate: $prevInsExpDate
            );
        } else {
            $this->assertEquals($expected, $this->fooEntity->prevInsInForce(
                prevInsExists: $prevInsExists,
                prevInsExpDate: $prevInsExpDate
            ));
        }
    }

    public function prevInsInForceDataProvider(): \Generator
    {
        yield 'Sin seguro anterior' => [new PrevInsExistsVO("NO"), "", "NO"];
        yield 'Con seguro anterior pero sin expiration date' => [
            new PrevInsExistsVO("SI"), "", WrongInputDataException::class
        ];
        yield 'Con seguro anterior y expiration date anterior' => [new PrevInsExistsVO("SI"), "2022-04-30", "NO"];
        yield 'Con seguro anterior y expiration date vigente' => [new PrevInsExistsVO("SI"), "2028-04-30", "YES"];
    }
}
