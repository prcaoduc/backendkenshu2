<?php
class Validator
{
    public function __construct()
    {
    }

    public static function blankValidator($input)
    {
        $error = [];
        $empty_fields = array_filter($input, array('self', 'emptyFilter'));
        foreach ($empty_fields as $key => $value) $error[$key] = 'blank';
        return $error;
    }
    
    public static function emptyFilter($val)
    {
        return empty($val);
    }
}
