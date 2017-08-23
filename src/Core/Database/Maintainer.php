<?php
namespace Core\Database;

use Core\Builder\Builder;
use Core\Plugin\Registrable;
use Go\ParserReflection\ReflectionClass;

class Maintainer
{

	/**
	 * @var Database
	 */
	private $database;

	/**
	 * @var \Doctrine\DBAL\Schema\AbstractSchemaManager
	 */
	private $schemaManager;

	/**
	 * Registrable constructor.
	 * @param Database $database
	 */
	public function __construct(Database $database)
	{
		$this->database      = $database;
		$this->schemaManager = $this->database->getSchemaManager();
	}

	public function updateTable(string $table, string $entity)
	{
		$columns = $this->schemaManager->listTableColumns($table);
		// Il faut analser la classe grace au builder afin d'avoir toutes propriétés et voir les quelles qu'il manque en base
		$entity = Builder::create($entity);
		foreach (get_object_vars($entity) as $property) {
			if (!in_array($property, $columns)) {
				// On peut rajouter la colonne
			}
		}
	}
}
