<?php
namespace App\Link\Table;

use Cake\ORM\Table;

class LinksTable extends Table
{

    public function initialize(array $config)
    {
        $this->belongsToMany('Tags');
    }
}
