<?php
//------------------------------------------------------------------
// file: ccontent.drv.php
// desc: defines the relationship of ccontent and cwidgetinstances 
//------------------------------------------------------------------

//////////////////
// functions
//////////////////

//////////////////////////////
// cwidgethooks
//////////////////////////////

//--------------------------------------------------------------------------------
// name: CWidgetHook_CContent_doInit()
// desc: initializes a cwidget's ccontent instances when the theme's site loads
//--------------------------------------------------------------------------------
function CWidgetHook_CContent_doInit($cwidgetinstance) {
	//alert("init: " . $cwidgetinstance->getWidgetID());
} // end CWidgetHook_CContent_doInit()
CWidgetHook :: hook("init", "CWidgetHook_CContent_doInit");

//--------------------------------------------------------
// name: CWidgetHook_CContent_doBody()
// desc: renders the body of the cwidget's ccontent 
//--------------------------------------------------------
function CWidgetHook_CContent_doBody($cwidgetinstance) {
} // end CWidgetHook_CContent_doInit()
CWidgetHook :: hook("body", "CWidgetHook_CContent_doBody");

//--------------------------------------------------------------------------------------
// name: CWidgetHook_CContent_doAdminInit()
// desc: initializes a cwidget's ccontent instances when the theme's dashboard loads
//--------------------------------------------------------------------------------------
function CWidgetHook_CContent_doAdminInit($cwidgetinstance) {
	//alert("admin_init: " . $cwidgetinstance->getWidgetID());
} // end CWidgetHook_CContent_doInit()
CWidgetHook :: hook("admin_init", "CWidgetHook_CContent_doAdminInit");

function ___path( $arr ){
	return implode("/",$arr);
}


//-----------------------------------------------------------
// name: CWidgetHook_CContent_doAdminBody()
// desc: renders the widget dashboard body of ccontent 
//-----------------------------------------------------------
function CWidgetHook_CContent_doAdminBody($cwidgetinstance) {
	
	throw new Exception("ERROR: CContent Problem");
	
	/*
	$type = $cwidgetinstance->getWidgetClassName();
	$num = $cwidgetinstance->getWidgetNumber();
	$path = ___path(array($type,$num));
	include_cwidget_memory($path, $path);
	$cmemory = use_memory($path);
	if($cmemory) {
		printbr("included memory");
		printbr("get_option($type):");
		print_r(get_option($type));
		printbr();
		printbr();
		printbr();		
		printbr("CMemory($path):");
		printbr($cmemory->toString());
	} // end if
	else alert("did not include memory");
	*/
} // end CWidgetHook_CContent_doForm()
CWidgetHook :: hook("admin_body", "CWidgetHook_CContent_doAdminBody");

///////////////////////////
// ccontent ajax scripts
///////////////////////////

//--------------------------------------------------
// name: CContent_doScripts()
// desc: include ccontent driver scripts
//--------------------------------------------------
function CContent_doScripts() {
    wp_enqueue_script( 'ccontent', relname( __FILE__ ) . '/ccontent.drv.js', array('jquery') );
} // end CContent_doScripts()
add_action('admin_enqueue_scripts', 'CContent_doScripts');

//--------------------------------------------------------------
// name: CContent_doCreateInstance()
// desc: creates ccontent instance and adds it to the widget
//--------------------------------------------------------------
function CContent_doCreateInstance() {
	$cwidget_id = $_POST["widget_id"];
	$ccontent_type = $_POST["ccontent_type"];	
	if ($cwidget_id == "" || $ccontent_type == "" || 
		!$ctheme = CTheme :: getCTheme()) {
		echo "ERROR: couldn't create new content.";
	} // end if
			
	// create a new content id		
	$ccontent_id = $ctheme->option("ccontent_id");
	$ccontent_id = ($ccontent_id === "") ? 0 : (((int)$ccontent_id)+1);
	
	// create instances and add it to this widgets instances
	$ccontent_instance = $ccontent_type . ":" . $ccontent_id;
	$ccontent_instances = $ctheme->option($cwidget_id);
	$ccontent_instances = ($ccontent_instances=="") ? $ccontent_instance : $ccontent_instances . "," . $ccontent_instance;
	$ccontent_cwidget_instances = $ctheme->option("ccontent_instances");
	$ccontent_cwidget_instances = ($ccontent_cwidget_instances=="") ? 
		$ccontent_instance : $ccontent_cwidget_instances . "," . $ccontent_instance;
	
	// store the instances into this widget
	$ctheme->option("ccontent_id", "".$ccontent_id);
	$ctheme->option("ccontent_instances", $ccontent_instances);
	$ctheme->option($cwidget_id, $ccontent_cwidget_instances);
	wp_die(); // terminate immediately and return a response
} // end CContent_doCreateInstance()
add_action("wp_ajax_ccontent_create", "CContent_doCreateInstance");
?>