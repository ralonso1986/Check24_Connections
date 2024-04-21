<?php

namespace App\Tests\Unit\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\Exception\WrongInputDataException;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\OccDriverVO;
use PHPUnit\Framework\TestCase;

class OccDriverVOTest extends TestCase
{
    public function testOccDriver(): void
    {
        $testOccDriver = new OccDriverVO("SI");
        $this->assertEquals("SI", (string) $testOccDriver);

        $testOccDriver = new OccDriverVO("NO");
        $this->assertEquals("NO", (string) $testOccDriver);
    }

    public function testWrongData(): void
    {
        $this->expectException(WrongInputDataException::class);
        $this->assertEmpty((string) new OccDriverVO("NOO"));
    }

    public function testIsOccDriver(): void
    {
        $testIsOccDriver = new OccDriverVO("SI");
        $this->assertTrue($testIsOccDriver->isOccDriver());
    }
}
