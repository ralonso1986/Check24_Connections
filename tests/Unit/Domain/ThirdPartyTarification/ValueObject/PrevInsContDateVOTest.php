<?php

namespace App\Tests\Unit\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsContDateVO;
use App\Connections\Domain\Exception\WrongInputDataException;
use PHPUnit\Framework\TestCase;

class PrevInsContDateVOTest extends TestCase
{
    public function testContDate(): void
    {
        $testDateNotMandatory = new PrevInsContDateVO("2024-12-12");
        $this->assertEquals("2024-12-12", (string) $testDateNotMandatory);
    }

    public function testEmptyData(): void
    {
        $this->assertEmpty((string) new PrevInsContDateVO(""));
    }

    public function testWrongData(): void
    {
        $this->expectException(WrongInputDataException::class);
        new PrevInsContDateVO("2023-20-12");

        $this->expectException(WrongInputDataException::class);
        new PrevInsContDateVO("test");
    }
}
