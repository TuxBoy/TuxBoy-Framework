<?php

namespace TuxBoy\Database;

/**
 * Class AnnotationType.
 *
 * Cette classe abstraite liste les annotations pour les entités par défaut, ça correspond à la liste qui se trouve dans
 * doctrineTypeMapping de doctrineDbal
 */
abstract class AnnotationType
{
    const DEFAULT = [
        'tinyint',
        'smallint',
        'mediumint',
        'int',
        'integer',
        'bigint',
        'tinytext',
        'mediumtext',
        'longtext',
        'text',
        'varchar',
        'string',
        'char',
        'date',
        'datetime',
        'timestamp',
        'time',
        'float',
        'double',
        'real',
        'decimal',
        'numeric',
        'year',
        'longblob',
        'blob',
        'mediumblob',
        'tinyblob',
        'binary',
        'varbinary',
        'set',
        'json'
    ];

    const DOCTRINE_MAPPING = [
        'tinyint'    => 'boolean',
        'smallint'   => 'smallint',
        'mediumint'  => 'integer',
        'int'        => 'integer',
        'integer'    => 'integer',
        'bigint'     => 'bigint',
        'tinytext'   => 'text',
        'mediumtext' => 'text',
        'longtext'   => 'text',
        'text'       => 'text',
        'varchar'    => 'string',
        'string'     => 'string',
        'char'       => 'string',
        'date'       => 'date',
        'datetime'   => 'datetime',
        'timestamp'  => 'datetime',
        'time'       => 'time',
        'float'      => 'float',
        'double'     => 'float',
        'real'       => 'float',
        'decimal'    => 'decimal',
        'numeric'    => 'decimal',
        'year'       => 'date',
        'longblob'   => 'blob',
        'blob'       => 'blob',
        'mediumblob' => 'blob',
        'tinyblob'   => 'blob',
        'binary'     => 'binary',
        'varbinary'  => 'binary',
        'set'        => 'simple_array',
        'json'       => 'json'
    ];
}
