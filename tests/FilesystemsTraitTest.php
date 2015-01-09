<?php

namespace WyriHaximus\Tests\FlyPie;

class FilesystemsTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilesystem()
    {
        $this->getMockForTrait('WyriHaximus\FlyPie\FilesystemsTrait')->filesystem('nonexisting');
    }
}
