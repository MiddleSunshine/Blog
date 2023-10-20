const path = require('path');

module.exports = {
    entry: './src/index.js', // 指定入口文件
    output: {
        filename: 'bundle.js', // 指定输出文件
        path: path.resolve(__dirname, 'dist')
    },
    devServer: {
        contentBase: './dist', // 指定服务器的根目录
        hot: true // 开启热更新
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/, // 匹配所有的js和jsx文件
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader' // 使用babel-loader来编译代码
                }
            }
        ]
    }
};
