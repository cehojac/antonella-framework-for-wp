# Creando Bloques de Gutenberg

Crear blocks de Gutenberg con Antonella Framework es sumamente sencillo, gracias a la dependencia 
[@wordpress/scripts](https://developer.wordpress.org/block-editor/packages/packages-scripts/) de wordpress.

Antes de continuar asegurate de terner instalado todas las dependencias necesarias, para ello ejecute el 
siguiente comando.

```bash
npm install
```

Éste comando instalará todas y cada una de las dependencias para trabajar con js moderno (.jsx).

## Creando mi primer block

```bash
php antonella block namespace/your-block [--callable] [--enque]
```

### Argumentos

```text
namespace: Tu Namespace para evitar confictos con otros blocks, si es omitido se usará como namespace antonella
your-block: El nombre de tu block a crear
--callable: Optional. Indica si queremos o no crear un block dinámico, renderizado desde php
--enque: Registra el block en la sección $gutenberg_blocks[] del fichero Config.php
```

### Ejemplo 1

```bash
php antonella block hello-word --enque
```

Éste comando hace varias cosas por nosotros.

1. Crea un fichero components/hello-word/index.js con la definición de nuestro block `antonella/hello-world` 
y sus respectivos ficheros de estilos uno para el editor (editor.css) y otro para el front-end (style.css) 
2. Actualiza el fichero index.js dentro del directorio components haciendo un import al block recien creado
3. Registra el block en la sección `$gutenberg_blocks[]` del fichero Config.php

components/hello-word/index.js

```js
const { registerBlockType } = wp.blocks;

import './editor.css';
import './style.css';

registerBlockType('antonella/hello-word',{
	title: 'Hello-word',
	description: 'My First Block with Gutenberg',
	icon: 'smiley',
	category: 'common',
	attributes: {},
	edit: props => {
		return (
			<h1>Hello World !!! from editor</h1>	
		);
	},
	save: props => {
		return (
			<h1>Hello World !!! from Front End</h1>
		);
	}
});
```

components/index.js

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

### Ejemplo 2 (Block dynamic)

A diferencia de los block's estáticos los block's dinámicos necesitan indicarle un callback mediante el attributo
`render_callback` del array asociativo `$gutenberg_blocks[]`. El sistema creará por nosotros dicha function en el
fichero src/Gutenberg.php como podemos observar en el código de arriba.

```bash
php antonella block namespace/dinamic --callable --enque
```

# Compilando

```bash
npm run start
```

Este comando ejecutará las tareas de WP-Scripts y creará de manera automática la carpeta `components/build` con
un index.js y ambos ficheros de estilos editor.css y style.css

El fichero index.js contiene el codigo de todos los block de gutenberg.

```bash
npm run build
```

Este comando optimiza el código (minificando el codigo de index.js)

```bash
npm run rebuild
```

Este comando hace todo de una vez optimiza y empaqueta el plugin ( es necesario haber instalado previamente
nuestro entorno local )

`php antonella serve`

--or

`php antonella test refresh` 

[Ver más](https://github.com/d3turnes/antonella-framework-for-wp/tree/1.8/docs/install.md)

# Links consultados
1 [Transpilando JavaScript fácilmente con wp-scripts](https://neliosoftware.com/es/blog/transpilando-javascript-facilmente-con-wp-scripts/)
2 [CREANDO TU PRIMER BLOQUE DE GUTENBERG CON WP-SCRIPTS, JAVASCRIPT Y REACT](https://codigoconjuan.com/creando-tu-primer-bloque-de-gutenberg-con-wp-scripts-javascript-y-react/)

[Volver al índice](https://github.com/d3turnes/antonella-framework-for-wp/tree/1.8/docs/readme.md)
