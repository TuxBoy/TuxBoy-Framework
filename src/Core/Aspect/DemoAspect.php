<?php

namespace Core\Aspect;

use Core\Database\Maintainer;
use Core\Database\Repository;
use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;

class DemoAspect implements Aspect
{

	/**
     * @param MethodInvocation $methodInvocation
     *
     * @Before("execution(public TuxBoy\Application\*\Controller\*->*(*))")
     */
    public function beforeExcecution(MethodInvocation $methodInvocation)
    {
    	/** @var $current_repository Repository */
    	$current_repository = current(array_filter($methodInvocation->getArguments(), function ($argument) {
			return $argument instanceof Repository;
		}));
    	if ($current_repository) {
    		$maintainer = new Maintainer($current_repository->getConnection());
			$table = $current_repository->getTableName();
			$maintainer->updateTable($table, $current_repository->getEntity());
		}
		echo 'Calling Before Interceptor ' . $methodInvocation->getMethod()->getName();
    }
}
