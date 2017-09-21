<?php
namespace Test\TuxBoy\Repository;

use TuxBoy\Database\Database;
use TuxBoy\Database\Repository;
use Doctrine\DBAL\Connection;
use Mockable;
use PHPUnit\Framework\TestCase;

class FakeRepository extends Repository
{

}

class RepositoryTest extends TestCase
{

	/**
	 * @var \Mockable
	 */
	private $database;

	/**
	 * @var FakeRepository
	 */
	private $repository;

	public function setUp()
	{
		$this->database = $this->getMockBuilder(Database::class)
			->disableOriginalConstructor()
			->setMethods(['fetch', 'fetchAll'])
			->getMock();
		$this->repository = new FakeRepository($this->database);
	}

	public function testgetClassName()
	{
		$this->assertEquals('FakeRepository', $this->repository->getClassName());
	}

}
