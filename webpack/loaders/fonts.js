module.exports ={
    test: /\.(woff|woff2|gif|eot|ttf)$/,
    use:{
        loader: 'file-loader',
        options:{
            outputPath: 'fonts',
        }
    }
}