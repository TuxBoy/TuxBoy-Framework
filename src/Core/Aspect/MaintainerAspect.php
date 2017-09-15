<?php

namespace Core\Aspect;

use Core\Database\Maintainer;
use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;

/**
 * Class MaintainerAspect.
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

    /**
     * Si true, la migration se fera automatiquement à l'actualisation de la page ou le
     * controller correspond à des entitées.
     *
     * @var bool
     */
    private $auto;

    public function __construct(Maintainer $maintainer, bool $debug, bool $auto)
    {
        $this->debug = $debug;
        $this->maintainer = $maintainer;
        $this->auto = $auto;
    }

    /**
     * @param MethodInvocation $methodInvocation
     *
     * @Before("execution(public App\*\Controller\*->*(*))")
     */
    public function beforeExcecution(MethodInvocation $methodInvocation)
    {
        if ($this->auto) {
            if (isset($methodInvocation->getThis()->entities)) {
                $entities = array_map(function ($entity) {
                    return $entity;
                }, $methodInvocation->getThis()->entities);
                if (!empty($entities)) {
                    foreach ($entities as $entity) {
                        $this->maintainer->updateTable($entity);
                    }
                }
            }

            if ($this->debug) {
                // echo ' Maintainer is running ' . $methodInvocation->getMethod()->getName();
            }
        }
    }
}
