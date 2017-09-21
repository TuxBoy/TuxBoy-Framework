<?php

namespace TuxBoy;

use Cake\ORM;
use TuxBoy\Exception\NotEntitySetterException;

/**
 * Class Entity.
 */
class Entity extends ORM\Entity
{
    /**
     * Entity constructor.
     *
     * @param array $properties
     * @param array $options
     */
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);
    }

    /**
     * @param array $properties
     */
    public function setData(array $properties)
    {
        $this->loadData($properties);
    }

    /**
     * @param array $data
     *
     * @throws NotEntitySetterException
     */
    private function loadData(array $data = [])
    {
        if (!empty($data)) {
            foreach ($data as $field => $value) {
                if (mb_substr($field, -3) === '_id') {
                    $foreignKeyToObject = str_replace(mb_substr($field, -3), '', $field);
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
