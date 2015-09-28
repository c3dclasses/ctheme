jQuery(document).ajaxSuccess(function(e,xhr,settings){
	var widget_id_base = 'mywidget';
	if(settings.data.search('action=save-widget') != -1 && settings.data.search('id_base='+widget_id_base) != -1){
		alert("yay!");
	} //end if 
});