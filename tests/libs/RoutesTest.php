<?php

use Symfony\Component\Yaml\Yaml;

class RoutesTest extends PHPUnit_Framework_TestCase
{
    protected $routes;

    public function getRoutes()
    {
        $routes = Yaml::parse(file_get_contents(CONFIG . '/routes.yml'));

        $route = SdkRouter::getInstance();
        $route->registerRoutes($routes);

        $out = array();
        $routes = $route->getRoutes();
        foreach ($routes as $k=>$v) {
            $out[] = array($k, $v);
        }

        return $out;
    }

    /**
     * @dataProvider getRoutes
     */
    public function test_config($k, $r)
    {
        $message = 'Route for '.$k.' should start with / ';
        $this->assertEquals(0, strpos($r['path'], '/'), $message);
    }
}
