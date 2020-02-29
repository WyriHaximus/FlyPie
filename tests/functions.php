<?php

namespace WyriHaximus\Tests\FlyPie;

function retrieveFactoryStringStaticChecker()
{
    $GLOBALS['THIS_callbackFiredStatic'] = true;
    return $GLOBALS['THIS'];
}
