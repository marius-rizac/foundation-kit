<?php


class SdkRouterTest extends PHPUnit_Framework_TestCase
{
    protected $routes;

    public function setUp()
    {
        $this->routes = SdkRouter::getInstance(true);

        $testRoutes = array(
            'homepage' => array(
                'path' => '/',
            ),
            'articles' => array(
                'path' => '/articles',
            ),
            'articles_details' => array(
                'path' => '/articles/details/{id}',
                'defaults' => array(
                    'id' => 0,
                ),
            ),
        );

        $this->routes->registerRoutes($testRoutes);
    }

    public function test_homepage()
    {
        $this->assertSame('/', $this->routes->generate('homepage'));
    }

    public function test_homepage_with_parameters()
    {
        $this->assertSame('/?login=1', $this->routes->generate('homepage', array('login' => 1)));
    }

    public function test_articles()
    {
        $this->assertSame('/articles', $this->routes->generate('articles'));
    }

    public function test_articles_details()
    {
        $this->assertSame('/articles/details/1', $this->routes->generate('articles_details', array('id' => 1)));
    }

    public function test_articles_details_plus_params()
    {
        $this->assertSame('/articles/details/1?title=my-article-title', $this->routes->generate('articles_details', array('id' => 1, 'title' => 'my article title')));
    }
}
