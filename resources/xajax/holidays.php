<?php

$xajax->registerFunction("addHolidayForm");
$xajax->registerFunction("editHolidayForm");
$xajax->registerFunction("addHoliday");
$xajax->registerFunction("updateHoliday");
$xajax->registerFunction("update2Holiday");
$xajax->registerFunction("deleteHoliday");
$xajax->registerFunction("delete2Holiday");
$xajax->registerFunction("listHolidays");


function addHolidayForm()
{
	$resp = new xajaxResponse();
	//$resp->assign("status", "innerHTML", "");

	$content ="<div class='col-md-12'><form method='post' class='panel panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 class="panel-title">REGISTER HOLIDAY</h4>
                                               		
                                           	 
                                        </div>
                                        <div class="panel-body">';
                                        	 
	$content .= '<div class="col-sm-6"><div class="form-group">
                                            <label class="control-label">Name of Holiday:</label>
                                            <div class=""><input type=text name="hname" id="hname" class="form-control"></div>                                            
                                            </div>'; 
			$content .= '<div class="form-group">
                                            <label class="control-label">Date:</label>
                                            <div class=""><input type=text name="date" id="date" class="form-control"></div>                                            
                                            </div>'; 
						
			$content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_addHolidayForm()\">Reset</button>
                                            <button type='button' class='btn btn-primary'  onclick=\"xajax_addHoliday(getElementById('hname').value, getElementById('date').value); return false;\">Save</button>";
                                            $content .= '</div><div id="status"></div></div></form></div>';
                                           $resp->call("createDate","date"); 
			
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addHoliday($hname, $date)
{	
	$content ='';
	//list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();	
	//$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	if ($hname == '')
	{
		$resp->alert("Please fill in the name of the holiday.");
		return $resp;
	}
	if(mysql_numrows(mysql_query("select * from holiday where name='".$hname."'")) > 0){
		$resp->alert("Holiday already exists. \nYou may want to just edit the date of the existing holiday.");
	}
	$ins_res = @mysql_query("insert into holiday (name, date) values ('".$hname."', '".$date."')");
	if (isset($ins_res) && @mysql_affected_rows() != -1)
	{
		$content .= "<font color=green>Holiday '$hname' successfully registered.</font><br>";
		$resp->assign("status", "innerHTML", $content);
		//$resp->call('xajax_addHolidayForm');
	}
	else
		$content .= "<font color=red>ERROR: Holiday '$hname' not registered.</font><br>";
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}


function listHolidays()
{
	$content ='';
	$resp = new xajaxResponse();
	$res = @mysql_query("select * from holiday order by date desc");
	if (@mysql_num_rows($res) > 0)
	{
		$i = 1;
		
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <p><h4 class="semibold text-primary mt0 mb5">LIST OF HOLIDAYS</h4></p>
                               
                               </div>';
 		$content .= '<table class="table table-bordered" id="table-tool">';
				 
		$content .= "<th>#</th><th>NAME OF HOLIDAY</th><th>DATE</th><th></th><tbody>";
				
		while ($row = @mysql_fetch_array($res))
		{
			$content .= "
				    <tr>
					<td>$i</td><td>$row[name]</td><td>$row[date]</td><td><a href='javascript:;' onclick=\"xajax_editHolidayForm(".$row[id]."); return false;\">Edit</a>&nbsp;&nbsp;<a href='javascript:;' onclick=\"xajax_deleteHoliday(".$row[id]."); return false;\">Delete</a></td>	
				    </tr>
				    ";
			$i++;
		}
		$content .= "</tbody></table></div>";
		$resp->call("createTableJs");
	}
	else
	{
		$cont = "<font color=red>No Holidays defined yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editHolidayForm($id)
{
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/

	$row = @mysql_fetch_array(@mysql_query("select *, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday from holiday where id = $id"));
		   
		   $content ="<div class='col-md-12'><form method='post' class='panel panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 class="panel-title">EDIT HOLIDAY</h4>
                                               		 <p class="text-default nm"></p>
                                           
                                        </div><div class="panel-body">';
                                        	 
	$content .= '<div class="col-md-6"><div class="form-group">
                                            <label class="control-label">Name of Holiday:</label>
                                            <div class=""><input type=text name="hname" id="hname" value="'.$row['name'].'" class="form-control"></div>                                            
                                            </div>'; 
			$content .= '<div class="form-group">
                                            <label class="control-label">Date:</label>
                                            <div class=""><input type=text name="date" id="date" value="'.$row['year'].'-'.$row['month'].'-'.$row['mday'].'" class="form-control"></div>                                            
                                            </div>'; 			
			
			$content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_listHolidays()\">Back</button>
                                            <button type='button' class='btn btn-primary'  onclick=\"xajax_updateHoliday('".$id."', getElementById('hname').value, getElementById('date').value); return false;\">Update</button>";
                                            $content .= '</div><div id="status"></div></div></form></div>';
                                           $resp->call("createDate","date"); 			
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateHoliday($id, $name, $date){
list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();
	if($name == ''){
		$resp->alert("You may not leave any filed blank");
		return $resp;
	}
	if (!$calc->isValidDate($mday, $month, $year))
	{
		$resp->alert('Invalid Date. Most likely wrong date for Month: Feb.');
		return $resp;
	}
	$resp->confirmCommands(1, "Do you really want to update?");
	$resp->call('xajax_update2Holiday', $id, $name, $date);
	return $resp;
}

function update2Holiday($id, $name, $date){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	if(! mysql_query("update holiday set name='".$name."', date='".$date."' where id='".$id."'"))
		$resp->alert("Could not update holiday");
	else{
		$action = "update holiday set name='".$name."', date='".$date."' where id='".$id."'";
		mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
		$resp->assign("status", "innerHTML", "<FONT COLOR=green>Holiday updated successfully</font>");
	}
	return $resp;
}

function deleteHoliday($id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}

	$resp->confirmCommands(1, "Do you really want to delete?");
	$resp->call('xajax_delete2Holiday', $id);
	return $resp;
}

function delete2Holiday($id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	if(! mysql_query("delete from holiday where id='".$id."'"))
		$resp->alert("ERROR: Could not delete the holiday");
	else{
		$action = "delete from holiday where id='".$id."'";
		mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
		$resp->assign("status", "innerHTML", "<FONT COLOR=RED>Holiday successfully deleted!</FONT>");
		$resp->call('xajax_listHolidays');
	}
	return $resp;
}

?>
