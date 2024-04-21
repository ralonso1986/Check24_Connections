<?php

namespace App\Tests\Unit\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\ThirdPartyTarification\ValueObject\HolderVO;
use App\Connections\Domain\Exception\EmptyInputDataException;
use PHPUnit\Framework\TestCase;

class HolderVOTest extends TestCase
{
    public function testHolder(): void
    {
        $testHolderVO = new HolderVO("CONDUCTOR_PRINCIPAL");
        $this->assertEquals("CONDUCTOR_PRINCIPAL", (string) $testHolderVO);
    }

    public function testEmptyData(): void
    {
        $this->expectException(EmptyInputDataException::class);
        $this->assertEmpty((string) new HolderVO(""));
    }

    public function testIsHolderMainDriver(): void
    {
        $testHolderVO = new HolderVO("CONDUCTOR_PRINCIPAL");
        $this->assertTrue($testHolderVO->isHolderMainDriver());
    }
}
