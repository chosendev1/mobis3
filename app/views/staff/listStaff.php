
<?php

if($staff <> 0){
?>
 <?=dataTableThis("List of Staff",$staff->result(),array("firstName","lastName","dob","gender","email","phone","country","picture"),array("edit","delete"));?>
 
<?php 
}
else{
?>
	  <?=tables::basic("All Staff",array(0 => array("Staff" => "No registered staff found.")));?>
	  
<?php
}
 function dataTableThis($table,$data,$disp=array(),$actions=array()){
 		
 		$content = ' <!--<div class="col-md-6 col-lg-12"> --> <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$table.'</h3>
                            </div>';
 		$content .= '<table class="table table-bordered" id="table-tools">';
 		if(count($data) > 0){
 			$content .= "<thead><th>#</th>";
 			foreach($data[0] as $name => $val){
 				if(count($disp) > 0){
 					if(in_array($name,$disp))
 						$content .= "<th>".ucfirst($name)."</th>"; 
 				}
 				else
 					$content .= "<th>".ucfirst($name)."</th>"; 
 				
 			}
 			$content .= "<th></th>";
 			$content .= "</thead>";
 		}
 		$content .="<tbody>";
 		$i = 1;
 		foreach($data as $ids => $val){
 			$content .= "<tr><td>".$i."</td>";
 			foreach($val as $key => $v){
 				if(count($disp) > 0){
 					if(in_array($key,$disp)){
 						if($key == "picture"){
 							if($v <> "")
 								$content .= "<td><img height='50' width='50' src='employeephotos/".$v."' alt='".$v."' /></td>";
 							else
 								$content .= "<td>".$v."</td>";
 						}
 					else
 						$content .= "<td>".$v."</td>";
 						
 					}
 				}
 				else{
 					//$content .= "<td>".$v."</td>";
 				}
 					
 			}
 			$content .=' <td class="text-center">
                                                <!-- button toolbar -->
                                                <div class="toolbar">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-default">Action</button>
                                                        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li><a href="javascript:;" onclick=\'xajax_editStaff("'.$val->employeeId.'");\'><i class="icon ico-pencil"></i>Edit</a></li>
                                                            <li class="divider"></li>
                                                            <li><a  href="javascript:;" onclick=\'xajax_deleteClientConfirm("'.$val->employeeId.'");\' class="text-danger"><i class="icon ico-remove3"></i>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!--/ button toolbar -->
                                            </td>';
 			$content .= "</tr>";
 			++$i;
 		}
 		$content .= "</tbody>";
 		$content .= "</table></div><!--</div>-->";
 		return $content;
 		
 	}
 	
 	?>
