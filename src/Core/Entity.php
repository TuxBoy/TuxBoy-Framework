<?php

namespace Core;

use Core\Exception\NotEntitySetterException;

/**
 * Class Entity
 */
class Entity
{

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            foreach ($data as $field => $value) {
                $setter = 'set' . ucfirst($field);
                if (!method_exists($this, $setter)) {
                    throw new NotEntitySetterException();
                }
                $this->$setter($value);
            }
        }
    }

}