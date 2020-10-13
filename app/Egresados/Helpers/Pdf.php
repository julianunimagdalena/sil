<?php namespace App\Egresados\helpers;

use Dompdf\Dompdf;

class Pdf{
    
    protected static $configured = false;
    
    public static function configure(){
        
        if(static::$configured)return;
        // disable DOMPDF's internal autoloader if you are using Composer
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        
        // include DOMPDF's default configuration
        require_once '../vendor/dompdf/dompdf/dompdf_config.inc.php';
        
        static::$configured=true;
    }
    
    public static function render($file, $html){
        static::configure();
        $dompdf = new Dompdf();
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream($file.".pdf", array('Attachment'=>0, 'compress'=>0));
    }
    
    public static function get($file, $html){
        static::configure();
        $dompdf = new Dompdf();
        $dompdf->load_html($html);
        $dompdf->render();
        return $dompdf->output();
        // $dompdf->stream($file.".pdf", array('Attachment'=>0, 'compress'=>0));
    }
    
    
    public static function show($file, $html){
        static::configure();
        $dompdf = new Dompdf();
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream($file.".pdf", array('Attachment'=>0, 'compress'=>0));
    }
    
    
}