const path = require('path');

module.exports = {

	entry: {
		SeoField: "./js/SeoField.js"
	},
	output: {
		path: path.join(process.cwd(), "../src/web/assets/js"),
		filename: "[name].min.js",
	},

	module: {
   rules: [
	 {
	   test: /\.js$/,
	   exclude: /node_modules/,
	   use: {
		 loader: "babel-loader"
	   }
	 }
   ]
 }
};
