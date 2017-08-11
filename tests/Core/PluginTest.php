<?php
namespace Test\Core;

use Core\Plugin;
use PHPUnit\Exception;
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

/**
 * Class PluginTest
 * @package Test\Core
 */
class PluginTest extends TestCase
{

	public function testAddBuilder()
	{
		$core_builder = [
			'core' => [
				Fake_Article::class => [
					Content::class
				]
			],
			'app' => []
		];
		Plugin::current()->addBuilder('core', $core_builder['core']);
		$this->assertEquals(1, count(Plugin::current()->getBuilders('core')));
		$this->assertTrue(Plugin::current()->hasBuilder('core'));
	}

}
