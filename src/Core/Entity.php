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
                if (substr($field, -3) === '_id') {
                    $foreignKeyToObject = str_replace(substr($field, -3), '', $field);
                    $field = $foreignKeyToObject;
                }
                $setter = 'set' . ucfirst($field);
                if (!method_exists($this, $setter)) {
                    throw new NotEntitySetterException();
                }
                $this->$setter($value);
            }
        }
    }

}
