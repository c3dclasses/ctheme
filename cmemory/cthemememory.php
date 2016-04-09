<?php 
//----------------------------------------------------------------
// file: cthemememory.php
// desc: defines the theme memory object 
//----------------------------------------------------------------
class CThemeMemory extends CMemory{	
	
	public function CThemeMemory(){ 
		parent :: CMemory(); 
	} // end CThemeMemory()
	
	public function open( $strpath, $params=NULL ){
		return parent :: open( $strpath, $params );	
	} // end open()
	
	public function close(){
		$this->save(); 
	} // end close()
	
	// create() / retrive() / update() / delete()
	public function create( $strname, $value, $strtype="" ){ 
		if(CTheme_option($strname) != "")
			return NULL;
		CTheme_option($strname, $value);
		return ( $this->save() ) ? array( "m_strname"=>$strname, "m_value"=>$value, "m_strtype"=>getTypeOf($value)) : NULL;
	} // end create()

	public function retrieve( $strname ){ 
		$value = CTheme_option($strname);
		return ( $this->restore() == FALSE || $value == "" ) ?
			"" : array("m_strname"=>$strname, "m_value"=>$value, "m_strtype"=>getTypeOf($value));
	} // end retrieve()
	
	public function update( $strname, $value, $strtype="" ){ 
		if( CTheme_option($strname) == "" )
			return NULL;
		CTheme_option($strname, $value);
		return ( $this->save() ) ? array( "m_strname"=>$strname, "m_value"=>$value, "m_strtype"=>getTypeOf($value)) : NULL;
	} // end update()

	public function delete( $strname ){ 
		if( CTheme_option($strname) == "" )
			return false;
		CTheme_removeOption($strname);
		return $this->save(); // update the file
	} // end delete()
	
	// misc. methods
	public function error(){
	} // end error()
	
	public function toString(){ 
		return print_r($this->convertCThemeOptionsToCMemory(),true); 
	} // end toString()
	
	public function toJSON(){ 
		return json_encode($this->convertCThemeOptionsToCMemory()); 
	} // end toString()
	
	public function serialize( $value ){ 
		return (gettype($value) == "object") ? serialize($value) : $value; 
	} // end serialize()
	
	public function unserialize( $value, $strtype ){ 
		return ($strtype == "object") ? unserialize($value) : $value; 
	} // end unserialize()  
	
	public function restore(){
		return true;
	} // end restore()
	
	public function save(){
		return true;		
	} // end save()
	
	protected function convertCThemeOptionsToCMemory(){
		$options = CTheme_options();
		$arr = NULL;
		foreach($options as $name=>$value){
			if(!$arr)
				$arr = array();
			$arr[$name] = array( "m_strname"=>$name, "m_value"=>$value, "m_strtype"=>getTypeOf($value));
		} // end foreach
		return $arr;
	} // end convertCThemeOptionsToCMemory()
} // end CThemeMemory

function include_ctheme_memory($strid, $strpath="localhost", $params=NULL){
	return include_memory($strid, $strpath, "CThemeMemory", $params);
} // end include_ctheme_memory()
?>