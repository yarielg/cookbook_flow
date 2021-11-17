const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = [
    new MiniCssExtractPlugin({
        filename: 'index.css'
    }),
    new VueLoaderPlugin()
]
