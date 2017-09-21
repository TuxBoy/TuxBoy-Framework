<?php

namespace TuxBoy\Database;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;
use TuxBoy\Annotation\Set;
use TuxBoy\Builder\Namespaces;
use TuxBoy\ReflectionAnnotation;

class Repository implements ObjectRepository
{
    protected static $TABLE = null;

    protected static $ENTITY = null;

    protected $defaultFetchMode = \PDO::FETCH_CLASS;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * Repository constructor.
     *
     * @param Database $connection
     */
    public function __construct(Database $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id the identifier
     *
     * @return object|null the object
     */
    public function find($id)
    {
        return $this->connection->fetch(
            "SELECT * FROM {$this->getTableName()} AS t WHERE t.id= ?",
            [$id],
            [],
            $this->getEntity()
        );
    }

    /**
     * Finds all objects in the repository.
     *
     * @return array the objects
     */
    public function findAll()
    {
        return $this->connection->fetchObject("SELECT * FROM {$this->getTableName()}", [], [], $this->getEntity());
    }

    /**
     * Créé un tableau indexé par l'id de l'élément.
     *
     * @return array
     */
    public function findList(): array
    {
        $items = [];
        foreach ($this->findAll() as $item) {
            $categories[$item->id] = $item;
        }

        return $items;
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @throws \UnexpectedValueException
     *
     * @return array the objects
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $where = '';
        foreach ($criteria as $field => $value) {
            $where .= $field . ' = ' . (is_string($value) ? "'$value'" : $value);
        }

        return $this->connection->fetchAll("SELECT * FROM {$this->getTableName()} WHERE {$where}");
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria the criteria
     *
     * @return object|null the object
     */
    public function findOneBy(array $criteria)
    {
        return current($this->findBy($criteria, null, 1));
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        return Namespaces::shortClassName(get_class($this));
    }

    /**
     * Récupère le nom de la table par rapport au nom du repository, si le nom de la table n'a pas été
     * définie manuelement via la constante $TABLE.
     *
     * @return string
     */
    public function getTableName(): string
    {
        if (null !== static::$TABLE) {
            return static::$TABLE;
        }
        $annotation = (new ReflectionAnnotation($this->getEntity()))->getClassAnnotation(Set::class);

        return $annotation->tableName;
    }

    /**
     * Récupère le nom de l'entité définie dans $ENTITY.
     *
     * @return string
     */
    public function getEntity(): ?string
    {
        if (null !== static::$ENTITY) {
            return static::$ENTITY;
        }

        return null;
    }

    /**
     * @return Database
     */
    public function getConnection(): Database
    {
        return $this->connection;
    }

    /**
     * @param $data mixed
     *
     * @return int|null
     */
    public function insert($data): ?int
    {
        // C'est une entity => on appelle la méthode write pour l'hydrater auto.
        if (is_object($data)) {
            $this->getConnection()->write($data, $this->getTableName());
        } else {
            $this->getConnection()->insert($this->getTableName(), $data);
        }

        return $this->getConnection()->lastInsertId();
    }

    /**
     * @param $data
     *
     * @return int
     */
    public function update($data): int
    {
        return $this->getConnection()->update($this->getTableName(), $data);
    }
}
