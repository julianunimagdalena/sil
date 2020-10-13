<?php namespace App\Egresados\Core;

class DPDFTemplates{
    
    public static function render($file, array $data = array()){
        ob_start();
        extract($data);
        require '../templates/pdf/'.$file.'.php';
        return ob_get_clean();
    }
    
    public static function render2($file, array $data = array(), array $data2 = array()){
        ob_start();
        extract($data);
        extract($data2);
        require '../templates/pdf/'.$file.'.php';
        return ob_get_clean();
    }
}

