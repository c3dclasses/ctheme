<?php
//---------------------------------------------------------------------------
// file: csection.drv.php
// desc: defines the driver functions and hooks
//---------------------------------------------------------------------------
include("csection.php");
include("cbodysection.php");

///////////////
// functions
function CSection_create($params) {
	register_sidebar($params->valueOf());
} // end CSection_create()

function CSection_destroy($id) { 
	unregister_sidebar($id);
} // end CSection_destroy()

function CSection_updateParams($id) {
	global $wp_registered_sidebars; 
	return ( $wp_registered_sidebars && isset( $wp_registered_sidebars[$id] ) ) ? $wp_registered_sidebars[$id] : NULL;	
} // end CSection_update()

function CSection_isActive($id) {
	return is_active_sidebar($id);
} // end CSection_isActive()

function CSection_doBody($csection) {
	$id = $csection->param('id'); 
	ob_start(); 
	if(is_active_sidebar($id))
		dynamic_sidebar($id); 
	return ob_end();
	//$str = ob_get_contents();
	//ob_end_clean();
	return $str;
} // CSection_doBody()
	
function CSection_getCWidgetInstances($csection) {
	$id=$csection->param('id');	
	if( ($sidebar_widget = wp_get_sidebars_widgets()) == NULL || isset( $sidebar_widget[$id] ) == false || ($widgets = $sidebar_widget[$id]) == NULL )
		return NULL;
	$cwidgetinstances=NULL;
	global $wp_registered_widgets;
	foreach($widgets as $index => $id) {
		$params = $wp_registered_widgets[$id];
		if( $cwidgetinstance = CWidgetInstance :: getCWidgetInstanceByID($id, $params) )
		//if( $cwidgetinstance = CWidgetInstance :: createCWidgetInstance($id, $params) )
			$cwidgetinstances[$id] = $cwidgetinstance;			
	} // end foreach()
	return $cwidgetinstances;
} // end CSection_getCWidgetInstances()

///////////
// hooks
$CSection_head=NULL;
$CSection_body=NULL;
$CSection_foot=NULL;
function CSection_constructMainSections() {
	global $CSection_head;
	global $CSection_body;
	global $CSection_foot;
	$CSection_head= new CSection();	// body section
	$CSection_head->tag("head");
	$CSection_head->addClass($CSection_head->id());
	$CSection_body = new CBodySection();	// body section
	$CSection_body->tag("body");
	$CSection_body->addClass($CSection_body->id());
	$CSection_foot = new CSection();	// body section
	$CSection_foot->tag("body");
	$CSection_foot->addClass($CSection_foot->id());
} // end CSection_initMainSections()
CHook :: add("construct", "CSection_constructMainSections");

function CSection_createMainSections() {
	global $CSection_head;
	global $CSection_body;
	global $CSection_foot;
	$CSection_head->create("head","head", "Widgets in this section will be shown on the head of the theme or document");
	$CSection_body->create("body","body", "Widgets in this section will be shown on the body of the theme or document");
	$CSection_foot->create("foot","foot", "Widgets in this section will be shown on the foot of the theme or document");
} // end createCSections()
CHook :: add("create", "CSection_createMainSections");

function CSection_doBodySection() {
	global $CSection_body;
	return ( $CSection_body ) ? $CSection_body->body() : "";	
} // end createCSections()
CHook :: add("csection_body", "CSection_BodySection");

function CSection_doAdminBodySection() {
	global $CSection_body;
	$CSection_body->admin_body();	
} // end createCSections()
CHook :: add("admin_body", "CSection_doAdminBodySection");
?>