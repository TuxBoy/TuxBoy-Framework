<?php

namespace TuxBoy\Aspect;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;
use Psr\Container\ContainerInterface;
use TuxBoy\Database\Maintainer;

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

    /**
     * MaintainerAspect constructor.
     *
     * @param Maintainer $maintainer
     * @param bool       $debug
     * @param bool       $auto
     */
    public function __construct(Maintainer $maintainer, bool $debug, bool $auto)
    {
        $this->debug = $debug;
        $this->maintainer = $maintainer;
        $this->auto = $auto;
    }

    /**
     * @param MethodInvocation $methodInvocation
     *
     * @Before("@execution(TuxBoy\Annotation\Maintainer)")
     */
    public function beforeAllExecution(MethodInvocation $methodInvocation)
    {
        if ($this->auto) {
            /** @var $container ContainerInterface */
            $container = current($methodInvocation->getArguments());
            if ($container->has('entities') && !empty($container->get('entities'))) {
                $entities = array_map(function ($entity) {
                    return $entity;
                }, $container->get('entities'));
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
