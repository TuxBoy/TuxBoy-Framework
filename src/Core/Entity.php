<?php

namespace Core;

use Core\Exception\NotEntitySetterException;

/**
 * Class Entity
 */
class Entity
{

    /**
     * Entity constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->loadData($data);
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->loadData($data);
    }

    /**
     * @param array $data
     * @throws NotEntitySetterException
     */
    private function loadData(array $data = [])
    {
        if (!empty($data)) {
            foreach ($data as $field => $value) {
                if (substr($field, -3) === '_id') {
                    $foreignKeyToObject = str_replace(substr($field, -3), '', $field);
                    $field = $foreignKeyToObject;
                }
                if ($field !== 'id') {
                    $setter = 'set' . ucfirst($field);
                }
                if (!method_exists($this, $setter)) {
                    throw new NotEntitySetterException();
                }
                $this->$setter($value);
            }
        }
    }

}
