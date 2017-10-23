<?php
namespace App\Link\Table;

use Cake\ORM\Table;

class TagsTable extends Table
{

    public function initialize(array $config)
    {
        $this->belongsToMany('Links');
    }
}
