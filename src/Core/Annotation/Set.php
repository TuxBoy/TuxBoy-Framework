<?php

namespace TuxBoy\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Initialize.
 *
 * @Annotation
 * @Annotation\Target("CLASS")
 */
class Set extends Annotation
{
    /**
     * @var
     */
    public $tableName;
}
