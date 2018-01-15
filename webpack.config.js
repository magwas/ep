var path = require('path');
var webpack = require('webpack');

module.exports = {
	entry: {
	    ep: './es6/main.js',
	    tests: './es6tests/Main.js'
	},
	output: {
	    filename: '[name].js',
	    path: __dirname + '/dist'
	},
    module: {
        loaders: [
            {
                loader: 'babel-loader',
                query: {
                  presets: 'es2015',
                },
            }
        ]
    },
    plugins: [
        // Avoid publishing files when compilation fails
        new webpack.NoErrorsPlugin()
    ],
    stats: {
        // Nice colored output
        colors: true
    },
    // Create Sourcemaps for the bundle
    devtool: 'source-map',
};
