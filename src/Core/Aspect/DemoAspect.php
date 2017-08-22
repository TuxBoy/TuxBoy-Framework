<?php

namespace Core\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;

class DemoAspect implements Aspect
{
    /**
     * @param MethodInvocation $methodInvocation
     *
     * @Before("execution(public TuxBoy\Application\Controller\HomeController->index(*))")
     */
    public function beforeExcecution(MethodInvocation $methodInvocation)
    {
        echo 'Calling Before Interceptor ' . $methodInvocation->getMethod()->getName();
    }
}
