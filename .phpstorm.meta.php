<?php
namespace PHPSTORM_META {

	use TuxBoy\Builder\Builder;
  use Psr\Container\ContainerInterface;

  $STATIC_METHOD_TYPES = [
		ContainerInterface::get('') => [
		 	'' == '@'
		],
		Builder::create('') => [
			'' == '@'
		],
		\PHPUnit_Framework_TestCase::createMock('') => [
			"" == "@|PHPUnit_Framework_MockObject_MockObject",
		],
		\PHPUnit_Framework_TestCase::getMock('') => [
			"" == "@|PHPUnit_Framework_MockObject_MockObject",
		],
	];

}
