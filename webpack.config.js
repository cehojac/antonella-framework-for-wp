const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const CopyPlugin = require("copy-webpack-plugin");
const PostCompile = require('post-compile-webpack-plugin');

const path = require( 'path' );
const fs = require('fs');

var plugins = defaultConfig.plugins;

plugins.push( 
  /* Mueve los ficheros recien construidos a su nueva ubicación */
  new PostCompile( () => {

    const assets = path.resolve(__dirname, 'assets/blocks/css');
    if (!fs.existsSync(assets)) fs.mkdirSync(assets,{ recursive: true });
    
    const files = [
		{from: path.resolve(__dirname, 'dev/components/compiled/index.css'), to: path.resolve(__dirname, 'assets/blocks/css/editor.css')},
		{from: path.resolve(__dirname, 'dev/components/compiled/style-index.css'), to: path.resolve(__dirname, 'assets/blocks/css/style.css')},
		{from: path.resolve(__dirname, 'dev/components/compiled/index.js'), to: path.resolve(__dirname, 'assets/blocks/index.js')},
		// Mueve ficheros estaticos
		{from: path.resolve(__dirname, 'node_modules/bootstrap/dist/css/bootstrap-grid.min.css'), to: path.resolve(__dirname, 'assets/css/bootstrap-grid.min.css')}
	];
    
    files.map( ({from, to}) => {
      fs.copyFile(from, to, (err) => { 
        if (err) throw err; 
        console.log(`El fichero ${from} fue copiado a ${to}`); 
      });
    });

  })
);

module.exports = {
  /** Carga la configuración por defecto */
  ...defaultConfig,
  entry: {
    index: path.resolve( __dirname, 'dev/components', 'index.js' ),
  },
  output: {
		filename: '[name].js',
		path: path.resolve( __dirname, 'dev/components/compiled' ),
	},
  module: {
    ...defaultConfig.module,
    rules: [
      ...defaultConfig.module.rules,
      {
        test: /\.svg$/,
        use: ["@svgr/webpack", "url-loader"]
      }
    ]
  },
  plugins: plugins,
};