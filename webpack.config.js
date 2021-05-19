/**
 * Webpack config 
 */
const webpack = require( 'webpack' )
const path = require( 'path' )
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' )

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
  plugins: [
    new webpack.ProvidePlugin( {
      React: 'react',
    } ),
    new MiniCssExtractPlugin( {
      filename: 'css/review-pus.[name].css',
    } ),
  ],
  module: {
    rules: [
      {
        test: /\.s[ac]ss$/i,
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
  }
}