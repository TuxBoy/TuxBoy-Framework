<?php

namespace TuxBoy\Database;

use Doctrine\DBAL\Connection;
use Go\ParserReflection\ReflectionClass;
use TuxBoy\Annotation\Set;
use TuxBoy\Builder\Builder;
use TuxBoy\Entity;
use TuxBoy\Exception\DatabaseException;
use TuxBoy\ReflectionAnnotation;

class Database
{
    /**
     * @var Connection
     */
    public $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Récupère les données depuis la BDD sous forme d'object, elle hydrate aussi les données si
     * l'entité est renseigné afin de pouvoir utiliser le Builder.
     *
     * @param string      $statement
     * @param array       $params
     * @param array       $types
     * @param string|null $class_name
     *
     * @return mixed
     */
    public function fetchObject(string $statement, array $params = [], array $types = [], string $class_name = null)
    {
        if (null === $class_name) {
            return $this->connection->executeQuery($statement, $params, $types)
                ->fetchAll(\PDO::FETCH_CLASS, $class_name);
        }
        $results = $this->connection->fetchAll($statement, $params, $types);
        if ($results) {
            foreach ($results as $key => $result) {
                $results[$key] = $this->hydrate($class_name, $result);
            }
        }

        return $results;
    }

    /**
     * Override pour hydrater les données retourné dans l'entité en question et l'instantie
     * avec le Builder.
     *
     * @param string $statement
     * @param array  $params
     * @param array  $types
     * @param string $class_name
     *
     * @return object
     */
    public function fetch($statement, array $params, array $types, string $class_name)
    {
        $result = $this->connection->fetchAssoc($statement, $params, $types);

        return $result ? $this->hydrate($class_name, $result) : $result;
    }

    /**
     * Hydrate les resultats obtenuent depuis la BDD dans la class en question.
     *
     * @param string $class_name
     * @param array  $result
     *
     * @throws DatabaseException
     *
     * @return object
     */
    private function hydrate(string $class_name, array $result)
    {
        $reflection = new ReflectionClass($class_name);
        $entity = Builder::create($class_name);
        foreach ($reflection->getProperties() as $property) {
            if ($this->isLinkProperty($entity, $property)) {
                $foreignEntity = (new ReflectionAnnotation($class_name, $property->getName()))->getAnnotation('var')->getValue();
                $table = (new ReflectionAnnotation($foreignEntity))->getClassAnnotation(Set::class)->tableName;
                $relations = $this->fetchObject("SELECT * FROM {$table} WHERE id = ?", [$result[$property->getName() . '_id']]);
                if ($relations) {
                    $setterName = 'set' . ucfirst($property->getName());
                    $entity->$setterName(current($relations));
                }
            }
            // Contruit à la volé le setter de la propriété afin de mettre à jour sa donnée
            $setter_name = 'set' . ucfirst($property->getName());
            if (!method_exists($entity, $setter_name)) {
                throw new DatabaseException('This entity does not setter ' . $setter_name);
            }
            $setter = $reflection->getMethod($setter_name)->getName();
            if (isset($result[$property->getName()])) {
                $entity->$setter($result[$property->getName()]);
            }
        }

        return $entity;
    }

    /**
     * @param Entity $entity
     * @param string $table
     *
     * @return int
     */
    public function write(Entity $entity, string $table)
    {
        $reflection = new \ReflectionClass($entity);
        $fields = [];
        $values = [];
        $set = [];
        foreach ($reflection->getProperties() as $property) {
            if (null !== $property->getValue($entity)) {
                if ($this->isLinkProperty($entity, $property)) {
                    $fields[] = $property->getName() . '_id';
                } else {
                    $fields[] = $property->getName(); // Nom des champs
                }
                $values[] = $property->getValue($entity);
                $set[] = '?';
            }
        }

        return $this->executeUpdate(
            "INSERT INTO {$table} (" . implode(', ', $fields) . ') 
            VALUES (' . implode(', ', $set) . ')',
            $values
        );
    }

    /**
     * Vérifie si la propriété en de l'entité est un lien de table (@link).
     *
     * @param Entity              $entity
     * @param \ReflectionProperty $property
     *
     * @return bool
     */
    private function isLinkProperty(Entity $entity, \ReflectionProperty $property): bool
    {
        $reflectionAnnotation = new ReflectionAnnotation($entity, $property->getName());

        return $reflectionAnnotation->hasAnnotation('link')
            && in_array($reflectionAnnotation->getAnnotation('link')->getValue(), ['belongsTo'], true);
    }
}
