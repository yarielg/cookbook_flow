const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const plugins = [
    require('postcss-nested')()
];

module.exports = {
    test: /\.css$/,
    use:[

        {
            loader: 'style-loader',
        },
        {
            loader: 'css-loader'
        },
        {
            loader: 'postcss-loader',
        },
        {
            loader: 'sass-loader',
        }
    ]
}