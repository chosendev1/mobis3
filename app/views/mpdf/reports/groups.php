<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun(){
 $from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
  $to_date2 = sprintf("%2d", $to_month);

$monthName = date('F', mktime(0, 0, 0, $to_date2, 10)); // March

    $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
        $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ $branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';}

//get loan product

if($account_name == ""){ $product_didplay = "All Products";} else{$product_didplay = $account_name;}

//report name
$reportname = "List of Groups"; 
//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];
$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Address: ".$sel['city']." ".$sel['address1']." <br> Telephone: ".$sel['telephone']." <br> Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

            //table
            $content = '<table width="100%"><tr>
            <td><img src="'.base_url().'logos/'.$sel['logo'].'" alt = "logo" style="width:5px; height:50px;padding-top:0px;"/></td>
           
            <td align=center width="50%"><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h2 style="color:red; " >'.$reportname.'</h2>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
             </td>
            </tr></table>';

return $content;
}

//body
function bodyfun($stuff){
  if($stuff != "All"){
    $sth = mysql_query("select distinct g.name as name, g.id as id from loan_group g join member m on g.id=m.group_id where m.last_name like '%".$stuff."%' or m.first_name like '%".$stuff."%' or g.name like '%".$stuff."%'");
    }else{
        $sth = mysql_query("select distinct g.name as name, g.id as id from loan_group g join member m on g.id=m.group_id");
    }

    $content = '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th align="left"><b>#</b></th><th align="left"><b>Group Name</b></th><th align="left"><b>Members</b></th></tr>
    <tr><td colspan="3"><hr></td></tr>';
    
    $x=1;
      while($row = mysql_fetch_array($sth)){
        ////$color=($i % 2==0) ? "lightgrey" : "white";
        $content .= "<tr><td>".$x."</td><td>".$row['name']."</td><td>";
        $mem_res = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no from group_member gm join member m on gm.mem_id=m.id where gm.group_id='".$row['id']."' order by m.mem_no");
        $i=1;
        while($mem = mysql_fetch_array($mem_res)){
          $content .= $i.". ".$mem['mem_no']." - ".$mem['first_name']. "  ". $mem['last_name']."<br>";
          $i++;
        }
        $content .= "</td></tr>";
        $x++;
      }
    $content .="</table>";
return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun().'<hr>
';
$headerE = '

';

$footer = '';


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($_GET['stuff']).'';

//==============================================================
//==============================================================
//==============================================================



//$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 

//$mpdf->SetDisplayMode('default');
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetWatermarkText("$fet[Fullname]");
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML($html);

$mpdf->Output();
exit;
//==============================================================
//==============================================================
//==============================================================


?>