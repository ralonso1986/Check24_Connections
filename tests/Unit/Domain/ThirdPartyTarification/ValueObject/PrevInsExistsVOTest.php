<?php

namespace App\Tests\Unit\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExistsVO;
use App\Connections\Domain\Exception\WrongInputDataException;
use PHPUnit\Framework\TestCase;

class PrevInsExistsVOTest extends TestCase
{
    public function testYesOrNot(): void
    {
        $testStringMandatory = new PrevInsExistsVO("SI");
        $this->assertEquals("SI", (string) $testStringMandatory);

        $testStringMandatory = new PrevInsExistsVO("NO");
        $this->assertEquals("NO", (string) $testStringMandatory);
    }

    public function testWrongData(): void
    {
        $this->expectException(WrongInputDataException::class);
        $this->assertEmpty((string) new PrevInsExistsVO("NOO"));
    }
}
