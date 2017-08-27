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

	/**
	 * @var bool
	 */
	private $debug;

	public function __construct(Database $database, bool $debug)
	{
		$this->database = $database;
		$this->debug = $debug;
	}

	/**
     * @param MethodInvocation $methodInvocation
     *
     * @Before("execution(public TuxBoy\Application\*\Controller\*->*(*))")
     */
    public function beforeExcecution(MethodInvocation $methodInvocation)
    {
    	if (isset($methodInvocation->getThis()->entities)) {
			$entities = array_map(function ($entity) {
				return $entity;
			}, $methodInvocation->getThis()->entities);
			if (!empty($entities)) {
				$maintainer = new Maintainer($this->database, $entities);
				$maintainer->updateTable();
			}
		}

		if ($this->debug) {
			echo 'Calling Before Interceptor ' . $methodInvocation->getMethod()->getName();
		}
	}
}
