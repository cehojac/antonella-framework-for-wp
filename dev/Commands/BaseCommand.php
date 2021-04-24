<?php

namespace Dev\Commands;

class BaseCommand extends \Symfony\Component\Console\Command\Command {
	
	private $paths;
	
	private $dir;

    protected $understant = '<comment>Antonella no understand you. please read the manual in https://antonellaframework.com</comment>';

    protected $testdir = "wp-test";
    
    protected $port = 8010;
	
	function __construct() {
		
		// call parent construct
		parent::__construct();
	
		$this->dir = \Console::$dir;	// recupera el directorio base
	
        //$this->dir = PLUGINDIR;

		$this->paths = [
            'controllers' => $this->dir.'/src/Controllers',
			'commands' => $this->dir.'/dev/Commands',
            'widgets' => $this->dir.'/src/Widgets',
            'helpers' => $this->dir.'/src/Helpers',
            'classes' => $this->dir.'/dev/Classes',
            'blocks' => $this->dir.'/dev/components',
            'stubs' => $this->dir.'/dev/stubs',
            'config' => $this->dir.'/src/Config.php',
            'gutenberg' => $this->dir.'/src/Gutenberg.php',
		];
		
	}
	
	public function getPath($dir, $file = '')
    {
        $dir = strtolower($dir);

        switch ($dir) {
            case 'stubs':
                $ext = '.stub';
                break;
            default:
                $ext = '.php';
        }

        return
            !empty($file) ?
                str_replace('\\', '/', $this->paths[strtolower($dir)].'/'.$file.$ext) :
                    str_replace('\\', '/', $this->paths[strtolower($dir)]);
    }
	
	
	/** 
      * devuelve el namespace (principal) desde composer.json 
    **/
	public function getNamespace() {
		$composer = file_get_contents($this->dir.'/composer.json');
        $composer_json = json_decode($composer);
        $psr = $composer_json->autoload->{'psr-4'};
        $namespace = substr(key($psr), 0, -1);

        return $namespace;
	}
	
	/** devuelve el directorio base */
	public function getDirBase() {
		return $this->dir;
	}
	
	
	/**
     * Busca y remplaza un valor sobre memoria.
     *
     * @param array $content Contenido del fichero donde cada posicon del aray contine una linea del fichero
     * @param array $search  Array asociativo que tiene como clave el valor a buscar y como valor el dato de remplazo, haciendo dicho remplazo sobre memoria
     */
    public function __search_and_replace(&$content, $search)
    {
        foreach ($content as $key => $line) {
            $value = preg_replace('/\s+/', '', $line);

            $coma = '';
            if (isset($content[$key + 1])) {
                $coma = substr(preg_replace('/\s+/', '', $content[$key + 1]), 0, 1) === '[' ? ',' : '';
            }

            $content[$key] = isset($search[$value]) ? $search[$value].$coma : $line;
        }
    }
	
	/**
     *	Añade un nuevo method a una clase, si el controlador no existe se crea.
     *
     *	@param array $data La Data para crear el Controlador y el Method
     */
    public function __append_method_to_class($data)
    {
        $args = 1;
        extract($data);
        $markwater = '}/*generatedwithantollenaframework*/';

        $controller = ltrim($class, sprintf('%s\\Controllers\\', $this->getNamespace()));
        $target = $this->getPath('controllers', $controller);

        if (file_exists($target)) {
            // append method
            $content = explode("\n", file_get_contents($target));
            $result = 'append method class';

            // patrones a buscar
            $pattern = [
                '}/*generatedwithantollenaframework*/',
                '}',
            ];

            $params = [];
            if ($args > 1 && isset($args)) {
                for ($i = 1; $i <= $args; ++$i) {
                    $params[] = '$arg'.$i;
                }
            }
            $args = implode(', ', $params);

            $found = false;
            foreach ($content as $key => $line) {
                $value = preg_replace('/\s+/', '', $line);
                if ($value === $pattern[0]) {
                    $found = true;
                    $content[$key] = sprintf("\tpublic static function %s(%s){\n\t\t//TODO\n\t}\n\n} /* generated with antollena framework */", $method, $args);
                    echo "Method Class Added\n";
                    break;
                }
            }

            if (!$found) {
                // buscamos la 1ra llave de cierre comenzando desde el final del fichero
                $i = count($content) - 1;
                while ($i >= 0 && !$found) {
                    $value = preg_replace('/\s+/', '', $line);
                    if ($value === $pattern[1]) {
                        $found = true;
                        $content[$key] = sprintf("\tpublic static function %s(%s){\n\t\t//TODO\n\t}\n\n} /* generated with antollena framework */", $method, $args);
                        echo "Method Class Added\n";
                        break;
                    }
                    --$i;
                }
            }

            $newContent = implode("\n", $content);
            file_put_contents($target, $newContent);
        } else {
            // crate controller and method class
            $result = 'created controller & method class';

            $classname = array_reverse(explode('\\', $class))[0];
            $namespace = rtrim($class, '\\'.$classname);

            $StubGenerator = $this->getNamespace().'\Classes\StubGenerator';
            $stub = new $StubGenerator(
                $this->getPath('stubs', 'controller-with-method'),	//__DIR__ . '/stubs/controller-with-method.stub',
                $target
            );

            $params = [];
            if ($args > 1 && isset($args)) {
                for ($i = 1; $i <= $args; ++$i) {
                    $params[] = '$arg'.$i;
                }
            }
            $args = implode(', ', $params);

            $stub->render([
                '%NAMESPACE%' => $namespace,
                '%CLASSNAME%' => $classname,
                '%METHOD%' => $method,
                '%PARAMS%' => $args,
            ]);
        }
    }
	
	
	public function slug_title($title, $fallback_title = '-') {
		require_once $this->testdir . '/wp-includes/formatting.php';
        
        return sanitize_title($title, $fallback_title);
    }
	
}