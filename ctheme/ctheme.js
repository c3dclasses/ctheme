//-----------------------------------------------------------------------------
// file: ctheme.js
// desc: c3dclasses system framework
//-----------------------------------------------------------------------------
var ctheme = CKernal.createCKernal("CKernal"); // create the programs in this documents
//if( ctheme ) alert("SUCCESS: CKernal.createCKernal()");
//else{ alert("ERROR: CKernal.createCKernal()"); }
ctheme.init();
$.noConflict();
// ready() -> load() -> unload().
jQuery(document).ready(function($){
	ctheme.ready();
}); // end ready() 
jQuery(window).load(function($){
	ctheme.load();
	ctheme.main();
}); // end load()
jQuery(window).resize(function($){
	ctheme.resize();
}); // end resize()
jQuery(window).unload(function($){
	ctheme.unload();
	ctheme.deinit();
	CKernal.destroyCKernal(
	ctheme);
}); // end unload()