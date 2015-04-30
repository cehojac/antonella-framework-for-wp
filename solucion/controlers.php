<?php
namespace App;

use App\database; //databases sentences  more info database.php
use App\templates\templates; //template sentences more info /templates/templates.php
use App\config; //initial configuration more info /config.php
use App\secure\forms\forms; //for verify token.. more info secure/forms/forms.php
use App\controlers;


/*Controlers*/

class controlers
{
    
    static public function home($lang)
    {
        
        
        //this table have especial characters in utf-8, so is necessary convert to ISO, in others querys you can set the convertions if do you like.
        $info=database::get('SELECT * FROM hospedajes ORDER BY id_link DESC','ISO-8859-15'); 
        templates::render('home',array('title'=>'Buscar Hospedaje','data'=>$info, 'chatset'=>CH_CHARSET, 'lang'=>$lang)); //more info templates/templates.php
    }
    static public function agregar_hospedajes($lang)
    {
        
       
        if(isset($_REQUEST['insert'])&&$_REQUEST['insert']=='yes')
        {
            $token=forms::verifytoken(CH_WORD,$_REQUEST['auth_token'],'','');
            $nombre_archivo='';
            
            if(isset($HTTP_POST_FILES['imagen']))
            {
                $nombre_archivo = $HTTP_POST_FILES['imagen']['name']; 
                $tipo_archivo = $HTTP_POST_FILES['imagen']['type']; 
                $tamano_archivo = $HTTP_POST_FILES['imagen']['size'];
                move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'], "public\assets\img\/". $nombre_archivo );
            }
            database::insert('hospedajes',
                array(
                    'nombre',
                    'tipo',
                    'ciudad',
                    'provincia',
                    'estrellas',
                    'apartamentos',
                    'tipo_habitacion',
                    'capacidad_apartamento',
                    'image'),
                array(
                    $_REQUEST['nombre'],
                    $_REQUEST['tipo'],
                    $_REQUEST['ciudad'],
                    $_REQUEST['provincia'],
                    $_REQUEST['estrellas'],
                    $_REQUEST['apartamentos'],
                    $_REQUEST['tipo_habitacion'],
                    $_REQUEST['capacidad_apartamento'],
                    $nombre_archivo)
            );
        }
        templates::render('agregar_hospedajes',array('title'=>'Agregar Hospedaje','chatset'=>CH_CHARSET, 'lang'=>$lang));
    }
    
    static public function ajax()
    {
        
        if(isset($_REQUEST['request'])&&$_REQUEST['request']=='search')
        {
            $data=database::get(
                "SELECT * FROM hospedajes 
                WHERE nombre LIKE '%".$_REQUEST['data']."%'
                OR ciudad LIKE '%".$_REQUEST['data']."%'
                OR provincia LIKE '%".$_REQUEST['data']."%'
                OR tipo_habitacion LIKE '%".$_REQUEST['data']."%'"
                ,CH_CHARSET);
           
            $html='<ul>';
            foreach($data as $dato)
            {
                $estrellas=$dato[5]<>0?$dato[5].' estrellas':'';
                $apartamento=$dato[6]<>0?$dato[6].' departamentos':'';
                $tipo=$dato[7]<>''?'habitación '.$dato[7]:'';
                $capacidad=$dato[8]<>0?$dato[8].' adultos':'';
                $html.='<li>'.$dato[2].' '.$dato[1].', '.$estrellas.$apartamento.', '.$tipo.$capacidad.', '.$dato[3].', '.$dato[4].'</li>';
            }
            $html.='</ul>';
            echo $html;
            die();
        }
    }
}
?>