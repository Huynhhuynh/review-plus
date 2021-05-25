/**
 * Webpack config 
 */
const { ProvidePlugin } = require( 'webpack' )
const path = require( 'path' )
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' )

module.exports = {
  mode: 'production',
  performance: {
    hints: false,
    maxEntrypointSize: 512000,
    maxAssetSize: 512000
  },
  entry: {
    frontend: './src/main.js',
    backend: './src/backend.js',
    cbFields: './src/cb-fields/loader.js'
  },
  output: {
    path: path.resolve( __dirname, 'dist' ),
    filename: 'review-plus.[name].bundle.js',
  },
  plugins: [
    new ProvidePlugin( {
      React: 'react',
      'wp.element': '@wordpress/element'
    } ),
    new MiniCssExtractPlugin( {
      filename: 'css/review-plus.[name].css',
    } ),
  ],
  module: {
    rules: [
      {
        test: /\.(scss|css)$/,
        use: [
          // Creates `style` nodes from JS strings
          // "style-loader",
          MiniCssExtractPlugin.loader, 
          // Translates CSS into CommonJS
          "css-loader",
          // Compiles Sass to CSS
          "sass-loader",
        ]
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader"
        },
      },
      {
        test: /\.(png|jpe?g|gif|svg)$/i,
        use: [
          {
            loader: 'file-loader',
          },
        ],
      },
    ]
  },
  externals: [
		'@wordpress/compose',
		'@wordpress/data',
		'@wordpress/element',
		'@wordpress/hooks',
		'@wordpress/i18n',
		'classnames',
		'lodash'
	].reduce( ( memo, name ) => {
		memo[ name ] = `cf.vendor['${ name }']`;
		return memo;
	}, {
		'@carbon-fields/core': 'cf.core'
	} ),
}