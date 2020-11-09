<?php
class Validator
{

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

    public static function imageValidator($input_files)
    {
        if (!isset($input_files))           return 'incorrect';
        if (empty($input_files['error']))   return 'error';

        $names = $input_files['name'];
        
        $sizes = $input_files['size'];
        
        $file_nums = count($names);
        
        $valid_ext = ['jpeg', 'png', 'jpg'];
        $max_size = 500000;
        for ($i = 0; $i < $file_nums; $i++) {
            $img_ext = strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
            
            if (in_array($img_ext, $valid_ext)) {
                if($sizes[$i] > $max_size) return 'invalidsize';
            }
            else return 'invalidext';
        }
    }
}
