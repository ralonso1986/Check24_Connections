<?php

namespace App\Tests\Unit\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\ThirdPartyTarification\ValueObject\PrevInsExistsVO;
use App\Connections\Domain\Exception\WrongInputDataException;
use PHPUnit\Framework\TestCase;

class PrevInsExistsVOTest extends TestCase
{
    public function testPrevInsExists(): void
    {
        $testPrevInsExists = new PrevInsExistsVO("SI");
        $this->assertEquals("SI", (string) $testPrevInsExists);

        $testPrevInsExists = new PrevInsExistsVO("NO");
        $this->assertEquals("NO", (string) $testPrevInsExists);
    }

    public function testExists(): void
    {
        $testExists = new PrevInsExistsVO("SI");
        $this->assertTrue($testExists->prevInsExists());
    }

    public function testWrongData(): void
    {
        $this->expectException(WrongInputDataException::class);
        $this->assertEmpty((string) new PrevInsExistsVO("NOO"));
    }
}
