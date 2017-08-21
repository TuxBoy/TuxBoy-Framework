<?php
namespace Test\Core;

use Core\Plugin\Plugin;
use Core\Plugin\Registrable;
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

class Fake_Plugin implements Registrable {

}

class Fake_Plugin_Test implements Registrable {

}

/**
 * Class PluginTest
 * @package Test\Core
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

}
