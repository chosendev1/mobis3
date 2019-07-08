



<div id="status"style="font-size:11px;">
<?php

if($clients <> 0){
?>
 <?=dataTableThis("All Clients",$clients->result(),array("companyId","companyName","country","city","address1","email","telephone","region1","website","newsletters1","certificate1","dateEstablished","status"),array("approve","disapprove","edit","delete"));?>
 
<?php 
}
else{
?>
	 
	  
<?php
}
 function dataTableThis($table,$data,$disp=array(),$actions=array()){
 		
 		$content = ' <!--<div class="col-md-6 col-lg-12"> --> <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$table.'</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
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
 			
 		}
 		$content .="<tbody>";
 		$i = 1;
 		foreach($data as $ids => $val){
 			$content .= "<tr><td>".$i."</td>";
 			foreach($val as $key => $v){
 				if(count($disp) > 0){
 					if(in_array($key,$disp))
 						$content .= "<td>".$v."</td>";
 				}
 				else
 					$content .= "<td>".$v."</td>";
 					
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
                                                            <li><a href="javascript:;" onclick=\'xajax_approveClient("'.$val->companyId.'");\'><i class="icon ico-thumbs-up"></i>Approve</a></li>
                                                            <li><a href="javascript:;" onclick=\'xajax_disapproveClient("'.$val->companyId.'");\'><i class="icon ico-thumbs-down"></i>Disapprove</a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="administration/editClient/'.$val->companyId.'"><i class="icon ico-pencil"></i>Edit</a></li>
                                                            <li><a  href="javascript:;" onclick=\'xajax_deleteClientConfirm("'.$val->companyId.'");\' class="text-danger"><i class="icon ico-remove3"></i>Delete</a></li>
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