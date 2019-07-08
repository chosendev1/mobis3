<?php
/*@fileName: tables.php
 *@date: 2014-09-26 11:55 CAT
 *@author: Noah Nambale [namnoah@gmail.com]
 *@description:
 *TODO
 *
 */
 
 class tables {

 	var $table;
 	var $data;
 	
 	public function basic($table,$data,$disp=array()){
 		$this->data = $data;
 		$content = ' <!--<div class="col-md-6 col-lg-12"> --> <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$table.'</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		if(count($this->data) > 0){
 			$content .= "<thead><th>#</th>";
 			foreach($this->data[0] as $name => $val){
 				if(count($disp) > 0){
 					if(in_array($name,$disp))
 						$content .= "<th>".ucfirst($name)."</th>"; 
 				}
 				else
 					$content .= "<th>".ucfirst($name)."</th>"; 
 				
 			}
 			$content .= "</thead>";
 		}
 		$content .="<tbody>";
 		$i = 1;
 		foreach($this->data as $ids => $val){
 			$content .= "<tr><td>".$i."</td>";
 			foreach($val as $key => $v){
 				if(count($disp) > 0){
 					if(in_array($key,$disp))
 						$content .= "<td>".$v."</td>";
 				}
 				else
 					$content .= "<td>".$v."</td>";
 					
 			}
 			$content .= "</tr>";
 			++$i;
 		}
 		$content .= "</tbody>";
 		$content .= "</table></div><!--</div>-->";
 		return $content;
 	}
 	
 	public function dataTable($table,$data,$disp=array(),$actions=array()){
 		$this->data = $data;
 		$content = ' <!--<div class="col-md-6 col-lg-12"> --> <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$table.'</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		if(count($this->data) > 0){
 			$content .= "<thead><th>#</th>";
 			foreach($this->data[0] as $name => $val){
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
 		foreach($this->data as $ids => $val){
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
                                                            <li><a href="javascript:void(0);"><i class="icon ico-pencil"></i>Update</a></li>
                                                            <li><a href="javascript:void(0);"><i class="icon ico-print3"></i>Print</a></li>
                                                            <li class="divider"></li>
                                                            <li><a href="javascript:void(0);" class="text-danger"><i class="icon ico-remove3"></i>Delete</a></li>
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
 
 }
?>
