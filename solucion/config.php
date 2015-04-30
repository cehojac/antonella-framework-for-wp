<?php namespace App;
/***********************************************************************************/
/*                                                                                 */
/*                                                                                 */
/*                           ARCHIVO DE CONFIGURACION                              */
/*                       Desarrollado por Carlos Herrera V.1                       */
/*                          Ultima Revisión: 06-04-2015                            */
/*                                                                                 */
/*                                                                                 */
/***********************************************************************************/

class config
{
    static function database()
    {
        // two options (mysql or mysqli) default: mysqli
        define('CH_DB_ACCESS','mysqli');
        //database name */
        define('CH_DB_NAME','destinia');
        //  database user 
        define('CH_DB_USER','root');
        // database pass
        define('CH_DB_PASSWORD', '');
        // databasehost 
        define('CH_DB_HOST', 'localhost');
        // database chatset
        define('CH_DB_CHARSET','UTF-8');
        return true;
    
    }
    
    static function charset($char)
    {
        static $charsets='';
        switch ($char)
        {
            case 'es-ES':
                $charsets='UTF-8';
            break;
            case 'en-EN':
                $charsets='UTF-8';
            break;
            case 'jp': //japon
                $charsets='Shift_JIS';
            case 'zh': //chinesse
                $charsets='BIG5-HKSCS';
            break;
            case 'arabic':
                $charsets='ISO-8859-6';
            break;
            default:
                 $charsets='UTF-8';
            break;
               
        }
        define('CH_CHARSET',$charsets);
        /*PO and MO files for translate*/
        putenv('LC_MESSAGES='.$char);
        $translatedir='/solucion/languages';
       
        setlocale(LC_ALL, $char);
        bindtextdomain('messages',$translatedir);
        textdomain('messages');
        return true;
    }
    static function forms()
    {
        define('CH_KEY','6399faaaa4e1ba62622eacadf58ae7f6'); //hash for tokens
        define('CH_WORD','send'); // secret word for forms
    }
    static function route()
    {
        $folder='/public/'; //carpeta donde esta alojada la web, se puede dejar en blanco
        $pageURL = 'http';
         if (isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
         $pageURL .= "://";
         if (isset($_SERVER["SERVER_PORT"])&&$_SERVER["SERVER_PORT"] != "80") {
          $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
         } else {
          $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
         }
        $data= explode('public/',$pageURL);
        return str_replace($folder,'',$data[1]);
    }
}


?>