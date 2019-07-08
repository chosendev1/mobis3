<?php
/*@fileName: forms.php
 *@date: 2014-11-20
 *@author: Noah Nambale [namnoah@gmail.com] 
 *TODO
 *get form header
 *get variables for settings
 *get form fields in array
 *create form
 *render form
 */
 
 class forms{
	
 	private $openTagFlag = true;
	
 	private $closeTagFlag = true;
	
 	private $method;
	 
 	private $action;
	
	private $formName;
	
	private $processor = "controlloler";
  	
  	private $processorName;
  	
  	private $formFields = array();
  	
  	private $content = "";
  	
  	private $hasUploads = false;
  	
  	private $heading = "Fill this Form";
  	
  	private $subHeading = "Fill this form and submit after completion";
  	
  	public function __construct($oTag=true, $cTag=true, $method="", $action="", $name="Form1", $processor="controller", $processorName="", $hasUploads=""){
  		$this->openTagFlag = $oTag;
  		$this->closeTagFlag = $cTag;
  		$this->method = $method;
  		$this->action = $action;
  		$this->formName = $name;
  		$this->processor = $processor;
  		$this->processorName = $processorName;
  		$this->hasUploads = $hasUploads;
  	}
  	
  	public function setHeadings($heading,$subHeading){
  		$this->heading = $heading;
  		$this->subHeading = $subHeading;
  	}
  	
  	public function addField($field = array()){
  		if(count($field) > 0)
  			array_push($this->formFields, $field);
  	}
  	
  	private function create(){
  		//opening tag
  		if($this->openTagFlag === true){
  			$this->content = '<div class="col-md-12"><form class="panel panel-default" ';
  			if($this->formName != "")
  				$this->content .= 'name="'.$this->formName.'" id="'.$this->formName.'"';
  			if($this->action != "")
  				$this->content .= 'action="'.$this->action.'" ';
  			if($this->method != "")
  				$this->content .= 'method="'.$this->method.'" ';
  			if($this->hasUploads === true)
  				$this->content .= 'enctype="multipart/form-data"';
  			$this->content .= ' data-parsley-validate>';
  				
  		}
  	 	$this->content .= '
  			  		<div class="panel-heading">
                              <h3 class="panel-title">'.$this->heading.'</h3>
                            </div>     
             <div class="panel-body">';
                                        
                                
               $lastContent = '<div class="right"><button type="reset" class="btn btn-default">Reset</button>';
                foreach($this->formFields as $field){
                 	if($field['type'] != "button" && $field['type'] != "submit" && $field['type'] != "heading"){
                 		$value = isset($field['value']) ? $field['value'] : NULL;
                 		$required = isset($field['required']) ? "required" : NULL;
                 		if($field['type'] != "checkbox")
                			$this->content .= ' <div class="form-group">
                                            <label class="col-sm-3 control-label">'.$field['lableName'].'</label>
                                            <div class="col-sm-9">';
                                		switch($field['type']){
                                			case "text":
                                                		$this->content .= '<input type="text" class="form-control" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$value.'" '.$required.' />';
                                                		break;
                                                	case "email":
                                                		$this->content .= '<input type="text" class="form-control" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$value.'" data-parsley-type="email" '.$required.' />';
                                                		break;
                                                	case "textarea":
                                                		$this->content .= '<textarea class="form-control" rows="3" placeholder="Content" name="'.$field['name'].'" id="'.$field['name'].'" '.$required.'>'.$value.'</textarea>';
                                                		break;
                                                	case "date":
                                                		$this->content .= ' <input type="text" class="form-control" id="datepicker4" value="'.$value.'" placeholder="Select a date" name="'.$field['name'].'" id="'.$field['name'].'" '.$required.' />';
                                                		break;
                                                	case "checkbox":
                                                		$this->content .= '<div class="form-group"> <div class="col-sm-6"><input type="checkbox" name="'.$field['name'].'" id="'.$field['name'].'" value="'.$field['value'].'" '.$required.' />'.$field['lableName'];
                                                		break;
                                                	case "radio":
                                                		foreach($field['options'] as $val => $display){
                                                			$checked =  $value==$val ? "CHECKED" : NULL; 
                                                			$this->content .= ' <label class="radio-inline">
                                                			<input type="radio" value="'.$val.'" name="'.$field['name'].'" id="'.$field['name'].'" '.$checked.' '.$required.'>'.$display.'
                                            				</label>';
                                            			}
                                            				 
                                            			break;
                                            		case "file":
                                            			/*$this->content .= ' <div class="input-group">
                                                <input type="text" name="'.$field['name'].'_" id="'.$field['name'].'_" class="form-control"  readonly>
                                                <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file" name="'.$field['name'].'" id="'.$field['name'].'">
                                                    </div>
                                                </span>
                                            </div>';*/
											$this->content .= ' <div class="input-group">
                                                
                                                <span>
                                                    <div>
                                                        <input type="file" name="'.$field['name'].'" id="'.$field['name'].'">                                                </div>
                                                </span>
                                            </div>';
                                            			break;
                                                	case "select":
                                                		if($field['nested'] === true){
                                                			$callBackDiv = isset($field['callbackdiv']) ? $field['callbackdiv'] : "div";
                                                			$callBackName = isset($field['callbackname']) ? $field['callbackname'] : "fake";
                                                			$this->content .= '<div id="'.$field['mydiv'].'">';
                                                			$this->content .= '<select class="form-control" name="'.$field['name'].'" id="'.$field['name'].'"  onchange="'.$field['fun'].'(this.value,\''.$field['calldiv'].'\',\''.$field['calledName'].'\',\''.$callBackDiv.'\',\''.$callBackName.'\')" '.$required.'>';
                                                		}
                                                		else
                                                			$this->content .= '<select class="form-control" name="'.$field['name'].'" id="'.$field['name'].'" '.$required.'>';
                                                		$this->content .= '<option value="">Select...</option>';
                                                		foreach($field['options'] as $key => $value)
                                                			$this->content .= '<option value="'.$key.'">'.$value.'</option>';
                                                		$this->content .= '</select>';
                                                		if($field['nested'] === true)
                                                			$this->content .= '</div>';
                                                	default: 
                                                		break;
                                                }
                       		$this->content .=                        '
                                                <p class="help-block">'.$field['desc'].'</p>
                                            </div>
                                         </div>';
                       }
                       if($field['type'] == "button" || $field['type'] == "submit"){
                       		$lastContent .= ' <button type="submit" class="btn btn-primary">Save changes</button>
                                    	';
                    	}
                    	
                    	if($field['type'] == "heading"){
                    		$this->content .= ' <div class="form-group header bgcolor-default">
                                            <div class="col-md-12">
                                                <h4 class="semibold text-primary nm">'.$field['heading'].'</h4>
                                            </div>
                                        </div>';
                    	}
                }
                $lastContent .= '</div>';
                $this->content .= $lastContent;
                $this->content .='</div>';
                if($this->closeTagFlag === true)
                	$this->content .= '</div></form>';
  	}
  
  	public function render(){
  		$this->create();
  		return $this->content;
  	}
  	
  	public function searchForm(){
  		return $this->content;	
  	}
 }
?>
