<?php

namespace App\Tests\Unit\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\Exception\WrongInputDataException;
use App\Connections\Domain\ThirdPartyTarification\ValueObject\OccDriverVO;
use PHPUnit\Framework\TestCase;

class OccDriverVOTest extends TestCase
{
    public function testYesOrNot(): void
    {
        $testStringMandatory = new OccDriverVO("SI");
        $this->assertEquals("SI", (string) $testStringMandatory);

        $testStringMandatory = new OccDriverVO("NO");
        $this->assertEquals("NO", (string) $testStringMandatory);
    }

    public function testWrongData(): void
    {
        $this->expectException(WrongInputDataException::class);
        $this->assertEmpty((string) new OccDriverVO("NOO"));
    }
}
