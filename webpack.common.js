const path = require('path');

module.exports = {
	
	context: path.resolve(__dirname),
	entry: "./public/javascript/eventspage/index.js",
	
	output: {
		path: path.resolve(__dirname, 'public/production/eventspage'),
		filename: 'index.js'
	},
	
	module: {
		rules: [
			{
				// loading JSX (aka Babel) into browser-friendly ES6
				test: /\.js$/,
				exclude: [
					/(node_modules|bower_components)/,
				],
				use: {
					loader: 'babel-loader',
					options: {
						presets: [
                            '@babel/preset-env',
                            '@babel/preset-react',
                        ],
                        plugins: ['@babel/plugin-transform-runtime']
					}
				}
			},
			{
				// load external resources (ie Google fonts)
                test: /\.(png|jpe?g|gif)$/i,
				use: {
                    loader: 'url-loader',
					options: {
                        limit: 100000,
                        esModule: false,
                    }
				}
			}
		]
	},
	
};
