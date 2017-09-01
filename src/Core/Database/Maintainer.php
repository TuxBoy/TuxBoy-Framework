<?php

namespace Core\Database;

use Core\Builder\Builder;
use Core\Builder\Namespaces;
use Core\Exception\AnnotationException;
use Core\Exception\DatabaseException;
use Core\Exception\MaintainerEcxeption;
use Core\ReflectionAnnotation;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;

/**
 * Maintainer.
 *
 * Le Maintainer permet de tenir à jour "maintenir" le schema de la BDD à jour, il se base sur les propriété
 * définie dans l'entité
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
     *
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
     * @param array $entities
     *
     * @return $this
     */
    public function setEntities(array $entities): self
    {
        $this->entites = $entities;

        return $this;
    }

    /**
     * Exécute la nouvelle version du schema définie dans schemaDefinition.
     *
     * @param string $entity
     * @param bool   $force Si true ça exécute la requête sinon ça on esite que la BDD est à jour
     */
    public function updateTable(string $entity, bool $force = true): void
    {
        $currentSchema = $this->database->getSchemaManager()->createSchema();
        $newSchema = clone $currentSchema;
        $schema = $this->schemaDefinition($newSchema, $entity);
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
     * Récupère le nom de la table définie dans l'entité via l'annotation @set.
     *
     * @param Schema $schema
     * @param string $entity
     * @return Table
     * @throws DatabaseException
     */
    private function getTable(Schema $schema, string $entity): Table
    {
        $reflectionAnnotation = new ReflectionAnnotation($entity);
        if (!$reflectionAnnotation->getAnnotation('set')->getValue()) {
            throw new DatabaseException('Not table name defined');
        }
        $setTable = $reflectionAnnotation->getAnnotation('set')->getValue();

        return $schema->hasTable($setTable)
            ? $schema->getTable($setTable)
            : $schema->createTable($setTable);
    }

    /**
     * Vérifie si la table a une clé primaire, si ça n'est pas le cas => créé.
     *
     * @param Table  $table
     * @param string $primaryKey Le nom de la clé étrangère
     * @param array  $options
     */
    private function createPrimaryKey(Table $table, string $primaryKey = 'id', array $options = []): void
    {
        $options = array_merge(['unsigned' => true, 'autoincrement' => true], $options);
        if (!$table->hasColumn($primaryKey)) {
            $table->addColumn($primaryKey, 'integer', $options);
            $table->setPrimaryKey([$primaryKey]);
        }
    }

    /**
     * Ajoute les colonnes à la table de l'entity, si la table esiste déjà, il va mettre à jour
     * uniquement les champs qui n'existent pas.
     *
     * @param Schema $schema Le nouveau schema
     *
     * @param string $entity
     * @return Schema
     * @throws AnnotationException
     * @throws MaintainerEcxeption
     */
    public function schemaDefinition(Schema $schema, string $entity): Schema
    {
            $table = $this->getTable($schema, $entity);
            $this->createPrimaryKey($table);
            $entity = Builder::create($entity);
            foreach (get_object_vars($entity) as $field => $value) {
                // On peut rajouter la colonne
                $reflectionAnnotation = new ReflectionAnnotation($entity, $field);
                $type_name = $reflectionAnnotation->getAnnotation('var')->getValue();
                $options = [];
                $options['length'] = $reflectionAnnotation->getAnnotation('length')->getValue()
                    ? $reflectionAnnotation->getAnnotation('length')->getValue()
                    : 255;
                if (!array_key_exists($field, $table->getColumns())) {
                    if (($type_name === '\DateTime') || ($type_name === 'DateTime')) {
                        $type_name = 'datetime';
                    }
                    if (
                        !$reflectionAnnotation->getAnnotation('link') &&
                        !in_array($type_name, AnnotationType::DEFAULT)
                    ) {
                        throw new AnnotationException('The annotation value does not exist ' . $type_name);
                    }
                    // Si la propriété à l'annotation link c'est une relation
                    if ($reflectionAnnotation->hasAnnotation('link')) {
                        // Récupère le type de relation passé en valeur du @link (belongsTo, hasMany..)
                        $foreignType = $reflectionAnnotation->getAnnotation('link')->getValue();
                        if (!method_exists($this, $foreignType)) {
                            throw new MaintainerEcxeption('The relation type method does not exit.');
                        }
                        $field = $this->$foreignType($schema, $table, $type_name);
                    }

                    if (!$this->isForeignKey($field)) {
                        $table->addColumn($field, $type_name, $options);
                    }
                } elseif (array_key_exists($field, $table->getColumns())) {
                    if (!$this->isForeignKey($field)) {
                        $table->changeColumn($field, $options);
                    }
                }
                $diff = array_diff_key($table->getColumns(), get_object_vars($entity));
                $fieldsDrop = array_filter($diff, function ($field) {
                    return $field !== 'id';
                }, ARRAY_FILTER_USE_KEY);
                if (!empty($fieldsDrop)) {
                    // il n'y a un champ encore en base qui n'est plus dans l'entity => drop
                    foreach ($fieldsDrop as $key => $default) {
                        // @TODO Il faudra gérer la suppression des relations
                        if (!$this->isForeignKey($key)) {
                            $table->dropColumn($key);
                        }
                    }
                }
            }

        return $schema;
    }

    /**
     * True if the field is a foreign key (e. field_id)
     *
     * @param string $field
     * @return bool
     */
    private function isForeignKey(string $field): bool
    {
        return boolval(substr($field, -3) === '_id');
    }

    /**
     * Créé une clé étrangère dans la table en question pour une relation simple avec suppression
     * en cascade et met la champ en index
     *
     * @param Schema $schema
     * @param Table  $table
     * @param string $className
     * @return string
     */
    public function belongsTo(Schema $schema, Table $table,  string $className): string
    {
        $field = strtolower(Namespaces::shortClassName($className)) . '_id';
        if ($this->isForeignKey($field) && !$table->hasColumn($field)) {
            // Récupère la table sur la quelle la clé étrangère fait référence
            $foreignTable = $this->getTable($schema, $className);
            $this->createPrimaryKey($foreignTable);
            $options['unsigned'] = true;
            $table->addColumn($field, 'integer', $options);
            if (!$table->hasIndex($field . '_index')) {
                $table->addIndex([$field], $field . '_index');
            }
            $table->addForeignKeyConstraint($this->getTable($schema, $className),
                [$field], ['id'], ['onDelete' => 'CASCADE'], $field . 'contrain'
            );
        }
        return $field;
    }

}
