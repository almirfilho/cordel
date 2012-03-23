function fileInputInit( path ){
	
	enhance({
		loadScripts: [
			path + 'jquery.fileinput.js',
			path + 'jquery.fileinput.start.js'
		]
	});
}