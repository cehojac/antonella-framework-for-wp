<?php namespace App\templates;

use App\templates\headers;
use App\templates\footer;
use App\templates\pages;
use App\secure\forms\forms;

/*this class charge the templates of the web. the templase stores in views folder*/

class templates
{
    public static function render($content, $data )
    {
       
       
        ob_start();
        
        $data?extract($data):false;
        include('views/header.php');
        include('views/'.$content.'.php');
        include('views/footer.php');
        $html=ob_get_clean();
        //insert tokens in forms. more info secure/forms/forms.php
        $html=str_replace('</form>','<input type="hidden" name="auth_token" value='.forms::maketoken(CH_WORD,'').' /></form>',$html);
        
        
        
        echo $html;  
    }
}


?>