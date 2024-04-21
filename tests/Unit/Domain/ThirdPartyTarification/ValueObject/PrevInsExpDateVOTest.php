<?php

namespace App\Tests\Unit\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExpDateVO;
use App\Connections\Domain\Exception\WrongInputDataException;
use PHPUnit\Framework\TestCase;

class PrevInsExpDateVOTest extends TestCase
{
    public function testExpDate(): void
    {
        $testDateNotMandatory = new PrevInsExpDateVO("2024-12-12");
        $this->assertEquals("2024-12-12", (string) $testDateNotMandatory);
    }

    public function testEmptyData(): void
    {
        $this->assertEmpty((string) new PrevInsExpDateVO(""));
    }

    public function testWrongData(): void
    {
        $this->expectException(WrongInputDataException::class);
        new PrevInsExpDateVO("2023-20-12");
    }
}
