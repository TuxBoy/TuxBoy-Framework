<?php
namespace Core\Aspect;

use Core\Database\Database;
use Core\Database\Maintainer;
use Core\Database\Repository;
use Core\ReflectionAnnotation;
use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Before;
use Go\ParserReflection\ReflectionClass;
use GuzzleHttp\Psr7\ServerRequest;

/**
 * Class MaintainerAspect
 */
class MaintainerAspect implements Aspect
{

	/**
	 * @var Database
	 */
	private $database;

	public function __construct(Database $database)
	{
		$this->database = $database;
	}

	/**
     * @param MethodInvocation $methodInvocation
     *
     * @Before("execution(public **->*(*))")
     */
    public function beforeExcecution(MethodInvocation $methodInvocation)
    {
    	/** @var $current_repository Repository */
    	$current_repository = current(array_filter($methodInvocation->getArguments(), function ($argument) {
			return $argument instanceof Repository;
		}));
    	if (isset($methodInvocation->getThis()->entities) && $current_repository) {
			$entities = array_map(function ($entity) {
				return $entity;
			}, $methodInvocation->getThis()->entities);
			if (!empty($entities)) {
				$maintainer = new Maintainer($current_repository->getConnection(), $entities);
				$maintainer->updateTable();
			}
		}
		echo 'Calling Before Interceptor ' . $methodInvocation->getMethod()->getName();
	}
}
