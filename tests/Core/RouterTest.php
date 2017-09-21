<?php
namespace Test\TuxBoy;

use TuxBoy\Router\Router;
use DI\Container;
use DI\ContainerBuilder;
use function DI\get;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class RouterTest extends TestCase
{

    /**
     * @var Router
     */
    private $router;

    public function setUp()
    {
        $this->router = new Router;
    }

    public function testGetMethodWithParamters()
    {
        $request = new ServerRequest('GET', '/blog/mon-slug-8');
        $this->router->get('/blog', function () { return 'Blog'; }, 'blog');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function () { return 'hello'; }, 'blog.view');
        $route = $this->router->match($request);
        $this->assertEquals('blog.view', $route->getName());
        $this->assertEquals('hello', call_user_func_array($route->getCallback(), [$request]));
        $this->assertEquals(['slug' => 'mon-slug', 'id' => '8'], $route->getParams());
        // Test invalid url
        $route = $this->router->match(new ServerRequest('GET', '/blog/mon_slug-8'));
        $this->assertEquals(null, $route);
    }

    public function testGenerateUri()
    {
        $this->router->get('/blog', function () { return 'Blog'; }, 'blog');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function () { return 'hello'; }, 'blog.view');
        $uri = $this->router->generateUri('blog.view', ['slug' => 'mon-slug', 'id' => 18]);
        $this->assertEquals('/blog/mon-slug-18', $uri);
    }

}
