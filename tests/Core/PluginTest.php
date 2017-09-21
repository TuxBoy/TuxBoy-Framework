<?php
namespace Test\TuxBoy;

use TuxBoy\Plugin\Plugin;
use TuxBoy\Plugin\Registrable;
use Go\Aop\Aspect;
use PHPUnit\Framework\TestCase;

trait Content {
	public $content;
}

class Fake_Article {

	/**
	 * @var string
	 */
	public $name;
}

class Fake_Plugin implements Aspect {

}

class Fake_Plugin_Test implements Registrable {

	/**
	 * Registrable constructor.
	 * @param array $configuration
	 */
	public function __construct(array $configuration = []) {  }
}

/**
 * Class PluginTest
 * @package Test\TuxBoy
 */
class PluginTest extends TestCase
{

	/**
	 * @var array
	 */
	private $core_builder;

	public function setUp()
	{

		$this->core_builder = [
			'core' => [
				Fake_Article::class => [
					Content::class
				]
			],
			'app' => [],
			'plugin' => [Fake_Plugin::class, Fake_Plugin_Test::class]
		];
	}

	public function testAddBuilder()
	{
		Plugin::current()->addBuilder('core', $this->core_builder['core']);
		$this->assertEquals(1, count(Plugin::current()->getBuilders('core')));
		$this->assertTrue(Plugin::current()->hasBuilder('core'));
	}

	public function testAddPlugin()
	{
		$plugin = Plugin::current();
		$plugin->addPlugin($this->core_builder['plugin']);
		$this->assertEquals(2, count($plugin->getPlugins()));
	}

	public function testGetPlugin()
	{
		$plugin = Plugin::current();
		$plugin->addPlugin($this->core_builder['plugin']);
		$this->assertEquals(Fake_Plugin::class, $plugin->getPlugin(Fake_Plugin::class));
		$this->assertEquals(Fake_Plugin_Test::class, $plugin->getPlugin(Fake_Plugin_Test::class));
	}

	public function testGetAspectPlugin()
	{
		$plugin = Plugin::current();
		$plugin->addPlugin($this->core_builder['plugin']);
		$this->assertEquals(1, count($plugin->getAspectPlugins()));
	}

}
