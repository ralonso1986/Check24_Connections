<?php

namespace App\Tests\Unit\Domain\ThirdPartyEntity;

use App\Connections\Domain\Exception\WrongInputDataException;
use App\Connections\Domain\ThirdPartyEntity\FooEntity;
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
        yield 'Conductor principal' => ["CONDUCTOR_PRINCIPAL", "YES"];
        yield 'No es conductor principal' => ["PRIMO_CONDUCTOR", "NO"];
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
        yield 'Conductor único' => ["CONDUCTOR_PRINCIPAL", "NO", "YES"];
        yield 'No es conductor único caso 1' => ["CONDUCTOR_PRINCIPAL", "SI", "NO"];
        yield 'No es conductor único caso 2' => ["PRIMO DEL CONDUCTOR PRINCIPAL", "NO", "NO"];
        yield 'No es conductor único caso 3' => ["PRIMO DEL CONDUCTOR PRINCIPAL", "SI", "NO"];
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
        yield 'Sin seguro anterior' => ["NO", "", "", "0"];
        yield 'Con seguro anterior pero sin contract date' => [
            "SI", "", "2024-04-30",
            WrongInputDataException::class
        ];
        yield 'Con seguro anterior pero sin expiration date' => [
            "SI", "2024-04-30", "",
            WrongInputDataException::class
        ];
        yield 'Con seguro anterior pero expiration date anterior a contract date' => [
            "SI", "2024-04-30", "2013-04-30",
            WrongInputDataException::class
        ];
        yield 'Con seguro anterior y fechas correctas' => ["SI", "2020-04-30", "2024-04-30", "4"];
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
        yield '0 conductores ocasionales' => ["CONDUCTOR_PRINCIPAL", "NO", "0"];
        yield '1 conductor ocasional caso 1' => ["CONDUCTOR_PRINCIPAL", "SI", "1"];
        yield '1 conductor ocasional caso 2' => ["PRIMO DEL CONDUCTOR PRINCIPAL", "NO", "1"];
        yield '2 conductores ocasionales' => ["PRIMO DEL CONDUCTOR PRINCIPAL", "SI", "2"];
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
        yield 'Sin seguro anterior' => ["NO", "", "NO"];
        yield 'Con seguro anterior pero sin expiration date' => [
            "SI", "", WrongInputDataException::class
        ];
        yield 'Con seguro anterior y expiration date anterior' => ["SI", "2022-04-30", "NO"];
        yield 'Con seguro anterior y expiration date vigente' => ["SI", "2028-04-30", "YES"];
    }
}
