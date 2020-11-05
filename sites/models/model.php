<?php
class Model{
    //not yet done
    function belongsToMany($model, $pivot_table, $target_pk, $start_id){
        require_once('models/'.lcfirst($model).'.php');
        $db = DB::getInstance();
        $target_table = lcfirst($model).'s';
        $query = 'SELECT * FROM $target_table INNER JOIN $pivot_table ON $target_table.id = $pivot_table'
         . '.' . '$target_pk INNER JOIN $';
        $obj = new $model;

        $filepath = substr($str, strrpos($str, '/') + 1);
        $klass = str_replace('_', '', ucwords($controller, '_')) . 'Controller';
        $str = '1-2-3-4-5';
        echo substr($str, strrpos($str, '/') + 1);
    }
}