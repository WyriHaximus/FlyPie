<?php

namespace WyriHaximus\Tests\FlyPie;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FilesystemsTraitTest extends TestCase
{
    public function testFilesystem()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->getMockForTrait('WyriHaximus\FlyPie\FilesystemsTrait')->filesystem('nonexisting');
    }
}
