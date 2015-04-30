<?php
/*index.php is the one archive show in public web, the php are in 'solucion' folder*/

use App\database; //databases sentences  more info database.php
use App\templates\templates; //template sentences more info /templates/templates.php
use App\config; //initial configuration more info /config.php
use App\secure\forms\forms; //for verify token.. more info secure/forms/forms.php
use App\controlers;
require ('../vendor/autoload.php');
$lang=(isset($_REQUEST['lang']) && $_REQUEST['lang']!="")?$_REQUEST['lang']:'es-ES'; //spanish default ;)
config::charset($lang); //see config.php
config::forms(); //see config.php



/************/
/*

       
        the templates stored in solucion/templates/views.
        you can make routes for other templates. this only is a example.
        Powered by Carlos Herrera 6-April-2015

*/


$route=config::route();

/*ROUTES*/
switch ($route)
{
    
    case '':
        controlers::home($lang);
    break;
    case 'agregar':
        controlers::agregar_hospedajes($lang);
    break;
    case 'ajax':
        controlers::ajax();
    break;
    /* you can add more case from friendly urls*/
    
    default:
        templates::render('404',array('lang'=>$lang,'chatset'=>CH_CHARSET));

}
?>