<?php

namespace App\Tests\Unit\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\Exception\WrongInputDataException;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsContDateExpDateVO;
use PHPUnit\Framework\TestCase;

class PrevInsContDateExpDateVOTest extends TestCase
{
    public function testContDateExpDate(): void
    {
        $testContDateExpDate = new PrevInsContDateExpDateVO("2020-12-12", "2024-12-12");
        $this->assertEquals(["2020-12-12", "2024-12-12"], explode(",", (string) $testContDateExpDate));
    }

    public function testEmptyData(): void
    {
        $this->assertEquals(",", (string) new PrevInsContDateExpDateVO("", ""));
    }

    /**
     * @dataProvider wrongDataProvider
     */
    public function testWrongData($prevInsContDate, $prevInsExpDate): void
    {
        $this->expectException(WrongInputDataException::class);
        new PrevInsContDateExpDateVO($prevInsContDate, $prevInsExpDate);
    }

    public function wrongDataProvider(): \Generator
    {
        yield 'contrat date incorrecto' => [
            "202-20-12", "2023-12-12"
        ];

        yield 'exp date incorrecto' => [
            "2022-10-12", "20234-12-12"
        ];

        yield 'contrat date mayor que exp date' => [
            "2025-12-12", "2023-12-12"
        ];
    }
}
