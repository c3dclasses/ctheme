<?php
//------------------------------------------------------------------------------------
// name: ctheme.drv.php
// desc: integrates ctheme with wordpress and c3dclassessdk
//------------------------------------------------------------------------------------

// includes
include("ctheme.php");

// funcitons
function CTheme_doDocType($ctheme) {
	$strhtml="html";
	return "<!DOCTYPE $strhtml>";
} // end CTheme_doDocType()

function CTheme_doTitle($ctheme) {
	ob_start();
	echo "<title>";		
	global $page, $paged;
	wp_title('|', true, 'right');
	bloginfo('name');
	$site_description = get_bloginfo('description', 'display');
	if ($site_description && (is_home() || is_front_page()))
		echo " | $site_description";
	if ($paged >= 2 || $page >= 2)
		echo ' | ' . sprintf(__('Page %s', 'twentyeleven'), max($paged, $page));
	echo "</title>";
	return ob_end();
}

function CTheme_doHTML($ctheme) {
	$id = $ctheme->getID();
	$class = $ctheme->getClass();
	ob_start();
?>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?> class="<?php echo $class; ?>" id="<?php echo $id; ?>">
<!--<![endif]-->
<?php
	return ob_end();
} // end CTheme_doHTML()

function CTheme_doHead($ctheme) {
	$stylesheet_url = get_bloginfo('stylesheet_url');		// the theme's style sheet
	$reset_url = relname(__FILE__) . "/cbase/reset.css";	// resets the theme's css
	$style = $ctheme->option("ctheme-style-text");
	$sitestyle = $ctheme->option("ctheme-site-style-text");
	$classstyle = $ctheme->option("ctheme-class-style-text");
	$id = $ctheme->getID();
	$class = $ctheme->getClass();
	
	$str = "\n<!-- BEGIN CTheme_doHead() -->\n";
	$str .= CTheme_doTitle($ctheme);
	$str .= "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$reset_url}\" />\n"; // reset.css
	$str .= "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$stylesheet_url}\" />\n"; // style.css	
	$str .= "\n<!-- Begin Wordpress Stuff -->\n";
	ob_start();
	wp_head();
	$str .= ob_end();
	$str .= "\n<!-- End Wordpress Stuff -->\n";
	$str .= "\n<!-- END CTheme_doHead() -->\n";
	$str .= "\n<!-- BEGIN CTheme_doStyle() -->\n";
	$str .= "<style>";
	$str .= "/* ctheme styles ////////////////////////////// */\r\n"; 
	$str .=	"{$sitestyle}\r\n";
	$str .= ".{$class}{{$classstyle}}\r\n";
	$str .= ".{$id}{{$classstyle}}\r\n";
	$str .= "#{$id}{{$style}}\r\n";
	$str .= "</style>";
	$str .= "\n<!-- END CTheme_doStyle() -->\n";	
	return $str;
} // end CTheme_doHead()

function CTheme_doFoot($ctheme) {
	$str = "\n<!-- BEGIN CTheme_doFoot() -->\n";
	ob_start();
	$str .= "\n<!-- Begin Wordpress Stuff -->\n";
	wp_footer();
	$str .= ob_end();
	$str .= "\n<!-- End Wordpress Stuff -->\n";
	$str .= "\n<!-- END CTheme_doFoot() -->\n";
	return $str;
} // end CTheme_doFoot()

function CTheme_doBody($ctheme) {
	//$cbody = $ctheme->getCBodySection();
	$str = CTheme_doDocType($ctheme);
	$str .= CTheme_doHTML($ctheme);	
	$str .= "<head>";
	$str .= CTheme_doTitle($ctheme);
	$str .= $ctheme->head(); 
	$str .= "</head>";
	$str .= CHook :: fire("csection_body");
	$str .= "</html>";
	return $str;
} // end CTheme_doBody()

function CTheme_doAdminBody($ctheme) {
	CHook :: fire("admin_body");
	CTheme_doCustomizeFuncs($ctheme);
} // end CTheme_doAdminBody()

function CTheme_getThemeName() {
	return str_replace(" ", "_", strtolower(preg_replace("/[^A-Za-z0-9 ]/", '', get_current_theme())));	
} // end  CTheme_getThemeName()

function CTheme_option($params) {
	if (func_num_args() == 1) 
		return get_theme_mod(func_get_arg(0)); 
	set_theme_mod(func_get_arg(0), func_get_arg(1)); 
} // end CTheme_option()

function CTheme_removeOptions($name) {
	remove_theme_mods($name);	
} // end CTheme_removeOptions()

function CTheme_removeOption($name) {
	remove_theme_mods($name);	
} // end CTheme_removeOption()

function CTheme_options() {
	return get_theme_mods(); 
} // end CTheme_option()

// checks if C3DClassesSDK exists
function CTheme_checkC3DClassesSDK() {
	return (class_exists("CKernal") == false) ? printerr("ERROR: C3DClassesSDK Plugin must be installed and activated before using CTheme") : true;
} // end CTheme_checkC3DClassesSDK()

// prints an error message
function CTheme_printError($message, $errno=E_USER_ERROR) {
    if (isset($_GET['action']) && $_GET['action'] == 'error_scrape') {
        echo '<strong>' . $message . '</strong>';
        exit;
	} // end if
	else {
        trigger_error($message, $errno);
    } // end else
	return false;
} // end CTheme_printError()

$CTheme_CustomizeFuncs;
function CTheme_addCustomizeFunc($strfunc){
	global $CTheme_CustomizeFuncs;
	if(!function_exists($strfunc))
		return;
	//if(!$CTheme_CustomizeFuncs)
	//	$CTheme_CustomizeFuncs=array();
	$CTheme_CustomizeFuncs[]=$strfunc;
} // end CTheme_addCustomizeFunc()

function CTheme_doCustomizeFuncs($ctheme){
	global $CTheme_CustomizeFuncs;
	if($CTheme_CustomizeFuncs == NULL)
		return;
	foreach($CTheme_CustomizeFuncs as $index => $strfunc){
		$strfunc($ctheme);
	} // end foreach
} // end CTheme_fireCustomizeFuncs()

// hooks
function CTheme_pluginActivation() {CTheme_checkC3DClassesSDK();}
register_activation_hook(__FILE__, "CTheme_pluginActivation");

function CTheme_pluginDeactivation() {}
register_deactivation_hook(__FILE__, "CTheme_pluginDeactivation"); 

function CTheme_support() {
	add_editor_style();
	add_theme_support('automatic-feed-links');
	add_theme_support('post-formats', array('aside', 'link', 'gallery', 'status', 'quote', 'image'));
	add_theme_support('post-thumbnails');
} // end CTheme_support()
add_action('after_setup_theme', 'CTheme_support');
add_action('admin_init', 'CTheme_support');

function CTheme_style() {} 
add_action('wp_head', 'CTheme_style');

function CTheme_sidebarAdminSetup() {} 
add_action('sidebar_admin_setup', 'CTheme_sidebarAdminSetup');
?>