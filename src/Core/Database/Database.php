<?php

namespace Core\Database;

use Core\Builder\Builder;
use Core\Entity;
use Core\Exception\DatabaseException;
use Core\ReflectionAnnotation;
use Doctrine\DBAL\Connection;
use Go\ParserReflection\ReflectionClass;

class Database extends Connection
{
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
            return $this->executeQuery($statement, $params, $types)
                ->fetchAll(\PDO::FETCH_CLASS, $class_name);
        }
        $results = $this->fetchAll($statement, $params, $types);
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
        $result = parent::fetchAssoc($statement, $params, $types);

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
     * @return int
     */
    public function write(Entity $entity, string $table)
    {
        $reflection = new \ReflectionClass($entity);
        $fields = [];
        $values = [];
        $set = [];
        foreach ($reflection->getProperties() as $property) {
            if (!is_null($property->getValue($entity))) {
                $fields[] = $property->getName(); // Nom des champs
                $values[] = $property->getValue($entity);
                $set[] = '?';
            }
        }
        return $this->executeUpdate(
            "INSERT INTO {$table} (". join(', ', $fields) .") 
            VALUES (". join(', ', $set) .")",
            $values
        );
    }
}
