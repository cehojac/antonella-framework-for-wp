<?php

namespace CH\Classes;

class Template {
	
	public static function render($template, $data = []) {
		
		$data?extract($data):false;
		 
		ob_start();
        include( MY_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'resources/templates' . DIRECTORY_SEPARATOR . $template . '.php');
        $content = ob_get_contents();
        ob_end_clean();
       
		return $content;		
	}
	
}