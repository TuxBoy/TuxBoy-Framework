<?php
namespace Core\Database;

use Core\Builder\Builder;
use Core\ReflectionAnnotation;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use PHPUnit\Runner\Exception;
use ReflectionClass;

/**
 * Maintainer
 *
 * Le Maintainer permet de tenir à jour "maintenir" le schema de la BDD à jour, il se base sur les propriété
 * définie dans l'entité
 *
 */
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
	 * @var array
	 */
	private $entites;

	/**
	 * Registrable constructor.
	 * @param Database $database
	 * @param array    $entites
	 */
	public function __construct(Database $database, array $entites = [])
	{
		$this->database = $database;
		$this->schemaManager = $this->database->getSchemaManager();
		$this->entites = $entites;

	}

	/**
	 * Exécute la nouvelle version du schema définie dans schemaDefinition
	 * @param bool $force Si true ça exécute la requête sinon ça on esite que la BDD est à jour
	 */
	public function updateTable(bool $force = true)
	{
		$currentSchema = $this->database->getSchemaManager()->createSchema();
		$newSchema = clone $currentSchema;
		$schema = $this->schemaDefinition($newSchema);
		// Compare le newSchema et le currentSchema afin de savoir s'il doit CREATE ou ALTER
		$migrationQueries = $currentSchema->getMigrateToSql($schema, $this->database->getDatabasePlatform());
		$this->database->transactional(function () use ($migrationQueries, $force) {
			foreach ($migrationQueries as $query) {
				if ($force) {
					$this->database->exec($query);
				}
			}
		});
	}

	/**
	 * Récupère le nom de la table définie dans l'entité via l'annotation @set
	 *
	 * @param Schema $schema
	 * @param string $entity
	 * @return Table
	 */
	private function getTable(Schema $schema, string $entity): Table
	{
		$reflection = new ReflectionClass($entity);
		$reflectionAnnotation = new ReflectionAnnotation($reflection->getDocComment());
		if (!$reflectionAnnotation->getAnnotation('set')->getValue()) {
			throw new Exception("Not table name defined");
		}
		$setTable = $reflectionAnnotation->getAnnotation('set')->getValue();
		return $schema->hasTable($setTable)
			? $schema->getTable($setTable)
			: $schema->createTable($setTable);
	}

	/**
	 * Ajoute les colonnes à la table de l'entity, si la table esiste déjà, il va mettre à jour
	 * uniquement les champs qui n'existent pas.
	 *
	 * @param Schema $schema
	 * @return Schema
	 * @throws \Exception
	 */
	public function schemaDefinition(Schema $schema): Schema
	{
		foreach ($this->entites as $entity) {
			$table = $this->getTable($schema, $entity);
			if (!$table->hasColumn('id')) {
				$table->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
				$table->setPrimaryKey(['id']);
			}
			$entity = Builder::create($entity);
			$reflection = new ReflectionClass($entity);
			$diff = array_diff_key($table->getColumns(), get_object_vars($entity));
			if (array_key_exists('id', $diff)) {
				unset($diff['id']);
			}
			foreach (get_object_vars($entity) as $field => $value) {
				if (!array_key_exists($field, $table->getColumns())) {
					// On peut rajouter la colonne
					$reflectionAnnotation = new ReflectionAnnotation($reflection->getProperty($field)->getDocComment());
					$type_name = $reflectionAnnotation->getAnnotation('var')->getValue();
					if (($type_name === '\DateTime') || ($type_name === 'DateTime')) {
						$type_name = 'datetime';
					}
					if (!in_array($type_name, AnnotationType::DEFAULT)) {
						throw new \Exception("The annotation value does not exist " . $type_name);
					}
					$options = [];
					if ($reflectionAnnotation->hasAnnotation('length')) {
						$options['length'] = $reflectionAnnotation->getAnnotation('length')->getValue();
					}
					$table->addColumn($field, $type_name, $options);
				}
				if (!empty($diff)) {
					// il n'y a un champ encore en base qui n'est plus dans l'entity => drop
					foreach ($diff as $field => $item) {
						$table->dropColumn($field);
					}
				}
			}
		}
		return $schema;
	}
}
