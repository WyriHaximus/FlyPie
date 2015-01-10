<?php

namespace WyriHaximus\Tests\FlyPie;

function testRetrieveFactoryStringStatic_checker()
{
    $GLOBALS['THIS_callbackFiredStatic'] = true;
    return $GLOBALS['THIS']->getMock('League\Flysystem\AdapterInterface');
}
