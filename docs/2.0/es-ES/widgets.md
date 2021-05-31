# Creación de Widgets

Antonella Frameworks nos permite crear widgets de una manera sencilla mediante el siguiente comando

```bash
php antonella make:widget MyWidget [--enque]
```

Este comando creará el controlador `MyWidget.php` dentro de la carpeta src/Widgets, opcionalmente podemos pasarle
el argumento --enque.

## Argumentos

--enque : Registra por nosotros el widget en la seccioón $widgets[] del fichero Config.php


## Salida 

src/Widgets/MyWidget.php
```php
<?php
namespace Antonella\CH\Widgets;
      
class MyWidget extends \WP_Widget
{
	/**
	 * Please complete the public variables
	 */
	public $name_widget=''; // <--complete this

	public $options=
	[
		'classname'=>'', // <-- complete this
		'description'=>'' // <-- complete this
	];

	public $form_values=
	[
		//Example: 'title'=>'the best plugin', 'url'=>'https://antonellaframework.com'
	];
   
	public function __construct()
	{
		parent::__construct('MyWidget', $this->name_widget, $this->options);
	}

	function form($instance) {
		// Build the Admin's Widget form
		$instance = wp_parse_args((array)$instance, $this->form_values);
		$html="";
		foreach ($instance as $key=>$inst)
		{
		$html.="<p>$key<input class='widefat' type='text' name='{$this->get_field_name($key)}' value='".esc_attr($inst)."'/></p>";
		}
		echo $html;
	}
	function update($new_instance, $old_instance) {
		// Save the Widget Options
		$instances = $old_instance;
		foreach($new_instance as $key => $value)
		{
			$instances[$key]= sanitize_text_field($new_instance[$key]);
		}
		return $instances;	
	}
	function widget($args, $instance) {
		//Build the code for show the widget in plubic zone.
		extract($args);
		$html="";
		// you can edit this function for make the html//
		//
		////////////////////////////////////////////////
		echo $html;
	}
} /* generated with antollena framework */
```

src/Config.php

```php
<?php
	
...

/**
 * Widget
 * For register a Widget please:
 * Console: php antonella Widget "NAME_OF_WIDGET"
 * @input array
 * @example public $widget = [__NAMESPACE__.'\YouClassWidget']  //only the class
 */
public $widgets = [ 
	[__NAMESPACE__ . '\Widgets\MyWidget']
];
```

[Volver al índice](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/readme.md)