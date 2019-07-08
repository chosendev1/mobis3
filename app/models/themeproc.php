<?
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
 	
 	public function getTheme(){
 		return $this->theme;
 	}
 	
 	
 }
 require_once("tables.php");
 function base_url(){
 	#return 'https://mobisapp.net/Test/';
        // return 'https://mobisapp.net'
 }
?>
