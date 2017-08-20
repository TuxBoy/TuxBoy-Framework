<?php
namespace Test\Core\Router;

use Core\App;
use Core\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    /**
     * @var Router
     */
    private $router;

    public function setUp()
    {
        $config = require dirname(dirname(dirname(__DIR__))) . '/config.php';
        $app    = new App($config);
        $this->router = $app->container->get(Router::class);
    }

    public function testRoutesNamed()
    {
        $this->router->get('/', function () { return 'lol'; }, 'root');
        $this->router->get('/article', function () { return 'lol'; }, 'post.index');
        $this->assertEquals('/', $this->router->getUrl('root'));
        $this->assertEquals('/article', $this->router->getUrl('post.index'));
    }

}