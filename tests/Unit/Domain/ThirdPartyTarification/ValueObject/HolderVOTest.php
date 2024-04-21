<?php

namespace App\Tests\Unit\Domain\ThirdPartyTarification\ValueObject;

use App\Connections\Domain\ThirdPartyTarification\ValueObject\HolderVO;
use App\Connections\Domain\Exception\EmptyInputDataException;
use PHPUnit\Framework\TestCase;

class HolderVOTest extends TestCase
{
    public function testHolder(): void
    {
        $testStringMandatory = new HolderVO("CONDUCTOR_PRINCIPAL");
        $this->assertEquals("CONDUCTOR_PRINCIPAL", (string) $testStringMandatory);
    }

    public function testEmptyData(): void
    {
        $this->expectException(EmptyInputDataException::class);
        $this->assertEmpty((string) new HolderVO(""));
    }
}
