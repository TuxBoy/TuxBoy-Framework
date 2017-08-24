<?php
namespace Core\Database;

use Core\Builder\Builder;
use Core\ReflectionAnnotation;
use Doctrine\DBAL\Schema\Schema;

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
	 * @param Database         $database
	 */
	public function __construct(Database $database)
	{
		$this->database      = $database;
		$this->schemaManager = $this->database->getSchemaManager();
	}

	/**
	 * Met à jour la structure de la base de données
	 *
	 * @TODO Gros réfactoring à faire
	 *
	 * @param string $table
	 * @param string $entity
	 */
	public function updateTable(string $table, string $entity)
	{
		$currentSchema = $this->schemaManager->createSchema();
		$entity = Builder::create($entity);
		$reflection = new \ReflectionClass($entity);
		// S'il n'y a pas de colonne, la table est nouvelle, on la créé.
		$newSchema = new Schema();
		$new_table = $newSchema->createTable($table);
		if (!$new_table->hasColumn('id')) {
			$new_table->addColumn('id', 'string');
			$new_table->setPrimaryKey(['id']);
		}
		foreach (get_object_vars($entity) as $field => $value) {
			// On peut rajouter la colonne
			$reflectionAnnotation = new ReflectionAnnotation($reflection->getProperty($field)->getDocComment());
			$type_name = $reflectionAnnotation->getAnnotation('var')->getValue();
			if (($type_name === '\DateTime') || ($type_name === 'DateTime')) {
				$type_name = 'datetime';
			}
			if ($reflectionAnnotation->hasAnnotation('length')) {
				$options['length'] = $reflectionAnnotation->getAnnotation('length')->getValue();
			}
			$new_table->addColumn($field, $type_name, $options);
		}
		$migrationQueries = $currentSchema->getMigrateToSql($newSchema, $this->database->getDatabasePlatform());
		$this->database->transactional(function () use ($migrationQueries) {
			foreach ($migrationQueries as $query) {
				$this->database->exec($query);
			}
		});
	}
}
