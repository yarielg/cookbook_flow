module.exports ={
    test: /\.(png|jpe?g|gif|svg)$/,
    use: [
        {
            loader: 'file_loader',
            options:{
                outputPath: 'images'
            }
        }
    ]
}