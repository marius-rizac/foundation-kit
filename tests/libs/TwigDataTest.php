<?php

use Symfony\Component\Yaml\Yaml;

class TwigDataTest extends PHPUnit_Framework_TestCase
{
    public function test_twigData_should_be_singleton()
    {
        $tda = TwigData::getInstance(array());
        $tda->setForcedPath('homepage');
        $tdb = TwigData::getInstance(array());

        $this->assertSame($tda->getForcedPath(), $tdb->getForcedPath());
    }
}
