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
	 * @var bool
	 */
	private $debug;

	/**
	 * @var Maintainer
	 */
	private $maintainer;

	public function __construct(Maintainer $maintainer, bool $debug)
	{
		$this->debug = $debug;
		$this->maintainer = $maintainer;
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
				$this->maintainer->setEntities($entities)->updateTable();
			}
		}

		if ($this->debug) {
			echo 'Maintainer is updated ' . $methodInvocation->getMethod()->getName();
		}
	}
}
