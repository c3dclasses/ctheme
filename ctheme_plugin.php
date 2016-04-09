<?php
/*
Plugin Name: Theme Developer - CTheme
Plugin URI: 
Description: Creates functional, dynamic wordpress theme. Powered by C3DClassesSDK(PHP).
Version: 1.0.0
Author: Kevin Lewis
Author URI: 
*/
?>
<?php
//------------------------------------------------------------------------------------
// name: ctheme_plugin.php
// desc: integrates ctheme with wordpress and c3dclassessdk
//------------------------------------------------------------------------------------

// includes
//$_BASE_PATH = "c3dclassessdk/usage/main.php";
//include_once(dirname(dirname($_SERVER['DOCUMENT_ROOT']))."/apps/c3dclassessdk/c3dclassessdk.php"); // include c3dclassessdk
include_once(dirname(dirname(__FILE__)) . "/c3dclassessdk/c3dclassessdk.php");
include_once("cform/cform.drv.php");
include_once("cmemory/cthemememory.php");
include_once("ctheme/ctheme.drv.php");
include_once("cmemory/cthemememory.php");
include_once("csection/csection.drv.php");
include_once("cwidget/cwidget.php");
include_once("cmemory/cwidgetmemory.php");
include_once("csectionwidget.php");
include_once("ccontent/ccontent.php");
include_once("ccontent/ccontent.drv.php");
?>