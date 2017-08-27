<?php

namespace Core\Database;

use Core\Builder\Namespaces;
use Core\ReflectionAnnotation;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;

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
        	"SELECT * FROM {$this->getTableName()} WHERE id= ?", [$id], [], $this->getEntity()
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
			$where .= $field . " = " . (is_string($value) ? "'$value'" : $value);
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
	 * définie manuelement via la constante $TABLE
	 *
	 * @return string
	 */
    public function getTableName(): string
	{
		if (!is_null(static::$TABLE)) {
			return static::$TABLE;
		}
		$docClass = (new \ReflectionClass($this->getEntity()))->getDocComment();
		return (new ReflectionAnnotation($docClass))->getAnnotation('set')->getValue();
	}

	/**
	 * Récupère le nom de l'entité définie dans $ENTITY.
	 *
	 * @return string
	 */
	public function getEntity(): ?string
	{
		if (!is_null(static::$ENTITY)) {
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
     * @param array $data
     * @return int|null
     */
	public function insert(array $data): ?int
	{
		$this->getConnection()->insert($this->getTableName(), $data);
		return $this->getConnection()->lastInsertId();
	}
}
