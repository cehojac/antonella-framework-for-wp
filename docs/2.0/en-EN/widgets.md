# Creating Widgets

Antonella Frameworks allows us to create widgets in a simple way just running the following command

```bash
php antonella make:widget MyWidget [--enque]
```

This command will create the `MyWidget.php` Controller inside the src/Widgets folder, optionally we can pass the
the --enque argument.

## Arguments

--enque : Register the widget in the $widgets[] section of the Config.php file.

## Output

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

[Go Back](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/en-EN/readme.md)
