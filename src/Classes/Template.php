<?php

namespace Antonella\CH\Classes;

class Template {
	
	public static function render($template, $data = []) {
		
		$data?extract($data):false;
		
		$dir = \plugin_dir_path(NELLA_URL);

		ob_start();
		$view = str_replace('\\', '/', sprintf("$1$s/resources/templates/%2$s.php", $dir, $template));
        	//include( $dir . DIRECTORY_SEPARATOR . 'resources/templates' . DIRECTORY_SEPARATOR . $template . '.php');
        include( $view );
		$content = ob_get_contents();
        ob_end_clean();
       
		return $content;		
	}
	
}