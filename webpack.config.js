/**
 * Webpack config 
 */
const webpack = require( 'webpack' )
const path = require( 'path' )

module.exports = {
  mode: 'production',
  entry: {
    frontend: './src/main.js',
    backend: './src/backend.js',
  },
  output: {
    path: path.resolve( __dirname, 'dist' ),
    filename: 'review-pus.[name].bundle.js',
  },
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
        use: [
          // Creates `style` nodes from JS strings
          "style-loader",
          // Translates CSS into CommonJS
          "css-loader",
          // Compiles Sass to CSS
          "sass-loader",
        ]
      },
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
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
  }
}