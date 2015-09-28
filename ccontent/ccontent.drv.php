<?php
//-----------------------------------------------------
// file: ccontent.drv.php
// desc: defines ccontent <=> cwidgetinstances link
//-----------------------------------------------------

//////////////////
// functions

/*
function CContent_create($cform, $id, $strccontenttype){
	if(!$cform || !$strccontenttype || !$id)
		return NULL;
	$cwidgetform = $cform->getCForm("ccontent-".$id, NULL, "CWidgetForm", "CWidgetOptions", "CWidgetControls");
	if(!$cwidgetform)
		return NULL;
	// allocate a new content type
	if(class_exists($strccontenttype)) 
		$ccontent = new $strccontenttype();
	else{
		 $ccontent = new CContentByCallbacks();
		 $ccontent->setCContentType($strccontenttype);
	} // end else
	if(!$ccontent)
		return NULL;
	// create and setup the content type
	$ccontent->create($cwidgetform); 
	$ccontent->init();	
	return $ccontent;		
} // end createCContent()

function CContent_init($cform) {
	// get the ccontent stored for this widget
	if( !$cform || !($coptions = $cform->getCOptions()) || 
	    ($strinstances = $coptions->option("ccontent-instances")) == "" ||	
		$strinstances == NULL || !($instances = explode(",",$strinstances)))
		return NULL;
	$ccontents = NULL;
	foreach($instances as $idandtype) {
		$idandtype = explode(":",$idandtype);	// get the id and type for each instance
		$id = $idandtype[0];
		$type = $idandtype[1];
		if($id == "" || $type == "")
			continue;
		if(($ccontent = CContent_create($cform,$id,$type)) != NULL) {
			$strname = $ccontent->getCForm()->getName();
			$ccontents[$strname] = $ccontent;
		} // end if
	} // end for
	return $ccontents;
} // end CContent_init()

//////////////////////////////////
// admin form helper methods

function CContent_requestHandler($cform){
	// check if cform exist
	if(!$cform || !($coptions = $cform->getCOptions()) || !($ccontrols = $cform->getCControls()))
		return false;
	// get the content type the user selected
	$type = $coptions->option("sel-ccontent-type");	
	if($type == "NULL")
		return false;
	// get the counter id
	if(($id = $coptions->option("ccontent-counter"))=="") 
		$id=0;
	// get the content instances for this widget
	$instances = $coptions->option("ccontent-instances");	
	if($instances=="" || $instances==NULL)
		$instances = $id.":".$type;
	else $instances .= ",".$id.":".$type;
	$coptions->option("ccontent-instances",$instances);
	$coptions->option("ccontent-counter",$id+1);		
	return true;
} // end CContent_requestHandler()

function CContent_createInstanceForm($cform) {
	if(!$cform || !($coptions = $cform->getCOptions()) || 
	   !($ccontrols = $cform->getCControls()))
		return NULL;
	// reset selected content type
	$coptions->option("content-type","NULL");
	// build the content form
	$ccontenttypes = CContent :: getCContentTypes();
	echo "<hr />";	
	echo "<p>";
	echo $ccontrols->label("sel-ccontent-type", "Create Content");
	echo $ccontrols->select("sel-ccontent-type", "NULL", $ccontenttypes);
	echo $ccontrols->hidden("ccontent-counter", "0");
	echo $ccontrols->hidden("ccontent-instances", "");
	echo '<input type="submit" value="Create" class="button button-primary widget-control-save right" id="widget-cwidget-8-savewidget" name="createwidget">';
	echo "</p>";
	return;	
} // end CContent_createInstanceForm()

function CContent_editInstanceForm($ccontent) {
	if(!$ccontent || !($coptions = $ccontent->getCOptions()))
		return;
	$id = $ccontent->getCForm()->getName();
	echo "<p>";
	echo "<form method='post' action=''>";
	echo '<input type="submit" value="Delete" class="button button-primary right" name="delete-ccontent-instance">';
	echo '<input type="hidden" value="'.$id.'" name="ccontent-instance">';
	echo "</form>";
	echo "</p>";
	return;
} // end CContent_editInstanceForm()
*/

//////////////////////////////
// cwidget hooks

CWidgetHook :: hook("init", "CContent_doInit");
function CContent_doInit($cwidgetinstance) { 
	if(!$cwidgetinstance)
		return;
	printbr("init ccontent here");
	//$cform = $cwidgetinstance->getCForm();
	//if(($ccontents=CContent_init($cform))!=NULL)
	// 	$cwidgetinstance->m_ccontents=$ccontents;
	return;
} // end CContent_doInit()

CWidgetHook :: hook("admin_body", "CContent_doForm");
function CContent_doForm($cwidgetinstance) {
	if(!$cwidgetinstance)
		return;
	$cform = $cwidgetinstance->getCForm();
	
	printbr("show ccontent form here");
	/*
	CContent_requestHandler($cform);		//
	CContent_createInstanceForm($cform);	// create an instance 
	CContent_doInit($cwidgetinstance);		// initialize all the ccontent instances
	if($ccontents = $cwidgetinstance->m_ccontents) {
		foreach($ccontents as $name => $ccontent) {
			$cform = $ccontent->getCForm();
			//if(delCContentInstanceFromRequest($cform)==false)
			//echoCContentInstanceMetaData($cform);
			$ccontent->admin_body();
			CContent_editInstanceForm($ccontent);
		} // end foreach
	} // end if
	*/
} // end CContent_doForm()
?>