const { merge } = require('webpack-merge');
const common = require('./webpack.common.js');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = merge(common, {
    mode: 'development',
    // we want source maps
    devtool: 'source-map',
    // set compiled css location
    module: {
		rules: [
			{
				// loading sass asset files
				test: /\.(sa|sc|c)ss$/,
				use: [
					'css-loader',
					'sass-loader',
				]
			}
		]
	},
	plugins: [
        // set compiled css location
        new MiniCssExtractPlugin({
            filename: 'index.css' 
        })
    ]
});