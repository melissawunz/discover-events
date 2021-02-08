const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const webpack = require('webpack');

module.exports = merge(common, {
    mode: 'production',
    output: {
        filename: 'index.min.js',
    },
    plugins: [
        new webpack.DefinePlugin({
			'process.env':{
				'NODE_ENV': JSON.stringify('production')
			}
        }),
        // set compiled css location
        new MiniCssExtractPlugin({
            filename: 'index.min.css' 
        })
    ],
    module: {
        rules: [
            {
                test: /\.js$/,
                use: {
                    loader: 'webpack-strip-block',
                    options: {
                        start: 'DEV-START',
                        end: 'DEV-END'
                    }
                }
            },
            {
				// loading sass asset files
				test: /\.(sa|sc|c)ss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'sass-loader',
				]
			}
        ],
    }
});