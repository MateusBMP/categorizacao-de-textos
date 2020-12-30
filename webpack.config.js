const path = require('path');

module.exports = {
  mode: 'development',
  devtool: 'eval-source-map',
  entry: {
    index: './src/index.js',
    app: './src/app.js'
  },
  output: {
    filename: './js/[name].bundle.js',
    path: path.resolve(__dirname, 'dist')
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
        ],
      }
    ]
  }
};