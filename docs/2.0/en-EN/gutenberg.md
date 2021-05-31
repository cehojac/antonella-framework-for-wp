# Creating Gutenberg Blocks

Creating Gutenberg Blocks with Antonella Framework is extremely easy thanks to the WordPress
dependency [@wordpress/scripts](https://developer.wordpress.org/block-editor/packages/packages-scripts/)

Before you continue make sure to have installed all needed dependencies, you can run the next command

```bash
npm install
```

This command will install every and each one of the dependecies to work with modern javascript (.jsx).

## Creating my first block

```bash
php antonella make:block namespace/your-block [--callable | -c] [--enque | -e]
```

### Arguments

```text
namespace: Required. Choose your own Namespace name to avoid conflicts with another blocks, Defaul Namespace name is antonella

your-block: Required. The name of the block you are creating
--callable: Optional. Indicates whether or not we want to create a dynamic block, rendered from php.
--enque: Register the block in the section $gutenberg_blocks[] of Config.php file.
```

### Example 1

```bash
php antonella make:block hello-word --enque
```

This command will do a lot of cool things for us.

1. Creates a file `dev/components/hello-word/index.js` with the block definition `antonella/hello-world`
   and also creates both style files , one for the editor (editor.css) and another for the frontend (style.css)
2. Updates the file index.js inside the folder components importing the recent created block
3. Register the block in the section `$gutenberg_blocks[]` of the Config.php file

dev/components/hello-word/index.js

```js
const { registerBlockType } = wp.blocks;

import "./editor.css";
import "./style.css";

registerBlockType("antonella/hello-word", {
  title: "Hello-word",
  description: "My First Block with Gutenberg",
  icon: "smiley",
  category: "common",
  attributes: {},
  edit: (props) => {
    return <h1>Hello World !!! from editor</h1>;
  },
  save: (props) => {
    return <h1>Hello World !!! from Front End</h1>;
  },
});
```

dev/components/index.js

```js
/* Add Components */
import "./hero";
import "./dinamico";
import "./hello-word";
```

src/Config.php

```php
/**
 * add Gutenberg's blocks
*/
public $gutenberg_blocks = [
	'antonella/hello-word' => [],
	'antonella/hero' => [],
	'antonella/dinamico' => [
		'attributes' => [
			'posts' => [
				'type' => 'array',
				'default' => []
			],
			'count' => [
				'type' => 'number',
				'default' => 3
			]
		],
		'render_callback' => __NAMESPACE__ . '\Gutenberg::antonella_dinamico_render_callback'
	],
	/*
	'antonella/example' => [		// namespace/block-name
		'editor_script' => '',		// opcional toma el mismo fichero js para todos los blocks
		'editor_style' => '',		// opcional style css for backend
		'style' => '',				// opcional style css for front-end
		'atrtibutes' => [],			// opcional, solo si tu block recibe atributos
		'render_callback' => ''		// opcional Function a renderizar en php, por default
									// __NAMESPACE__ . '\Gutenberg::namespace_block-name_render_callback
	]
	*/
];
```

### Example 2 (Block dynamic)

Unlike static blocks, dynamic blocks need to be given a callback via the `render_callback` attribute of the associative array `$gutenberg_blocks[]`.
`render_callback` of the associative array `$gutenberg_blocks[]`. The system will create such a function for us in the
src/Gutenberg.php file as we can see in the code above.

```bash
php antonella make:block namespace/dinamic --callable --enque
```

# Compiling

```bash
npm run start
```

This command will run WP-Scripts tags and automatically creates the folder `components/build` with an index.js file and
both style files editor.css and style.css

The index.js file contains the Gutenberg block's code

```bash
npm run build
```

This command will optimize the code (minifying the index.js file)

```bash
npm run rebuild
```

This command does everything at once, optimizes and package the plugin (you need to have your local environment already set up)

`php antonella serve`

--or

`php antonella refresh`

[See also](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/en-EN/install.md)

Useful Links

1 [Easily transpiling JavaScript with wp-scripts ](https://neliosoftware.com/es/blog/transpilando-javascript-facilmente-con-wp-scripts/)
2 [Creating your first Gutenberg Block with wp-scripts, JavaScript and React ](https://codigoconjuan.com/creando-tu-primer-bloque-de-gutenberg-con-wp-scripts-javascript-y-react/)

[Go Back](https://github.com/cehojac/antonella-framework-for-wp/tree/2.0/docs/2.0/en-EN/readme.md)
