//-----------------------------------------------------------------------------
// file: ctheme.js
// desc: c3dclasses system framework
//-----------------------------------------------------------------------------
var ctheme; 
(function($) {
	ctheme = CKernal.createCKernal("CKernal"); // create the programs in this documents
	ctheme.init();
	// ready() -> load() -> unload().
	$(document).ready(function($){
		ctheme.ready();
	}); // end ready() 
	$(window).load(function($){
		ctheme.load();
		ctheme.main();
	}); // end load()
	$(window).resize(function($){
		ctheme.resize();
	}); // end resize()
	$(window).unload(function($){
		ctheme.unload();
		ctheme.deinit();
		CKernal.destroyCKernal(ctheme);
	}); // end unload()
}($)); // end function($)