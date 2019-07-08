<?php
/*@fileName: themeProc.hp
 *@date: 2014-09-15 09:54 CAT
 *@author Noah Nambale
 *TODO
 *get selected theme
 *get common js
 *get common css
 *write table handler
 */
 
 class themeproc {
 
 	public $theme;
 	public $js = array();    
 	public $css = array(); 
 	
 	public function __construct($theme="",$js=array(),$css=array()){
 		$this->theme = $theme;
 		$this->js = $js;
 		$this->css = $css;
 	}
 	
 	public function renderJs(){
 		$content = "";
 		foreach($this->js as $script){
 			$content .= "<script language='javascript' src='".$script."'></script>\n";
 		}
 		echo $content;
 	}
 	
 	public function renderCSS(){
 		$content = "";
 		foreach($this->css as $css){
 			$content .= "<link type='text/css' rel='stylesheet' href='".$css."'>\n";
 		}
 		echo $content;
 	}
 	
 }
 require_once("tables.php");
 function base_url(){
        # return '/mobisapp/';
        # return 'http://m3.mobisapp.net/mobisapp/';
        //return 'http://197.221.128.82/mobisapp/';
        return 'http://mobis3.test/';
        #return 'https://mobisapp.net/';
 }
?>
