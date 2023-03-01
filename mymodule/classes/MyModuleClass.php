<?php

class MyModuleClass extends ObjectModel
{

    public $id_parameter;

    public $parameter;

    public static $definition = array(
        'table' => 'mymodule',
        'primary' => 'id_parameter',
        'multilang' => false,
        'fields' => array(
            'id_parameter' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'parameter' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true)
        )
    );

}