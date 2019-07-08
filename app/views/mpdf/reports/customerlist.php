<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun($loan){
 

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
if($loan == 0){
$reportname = "List of Customers"; 
}elseif($loan == 1){
$reportname = "List of Customers with loans"; 
}
elseif($loan == 2){
$reportname = "List of Customers without loans"; 
}
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
$logo = $sel['logo'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

            //table
            $content = '<table width="100%"><tr>
            <td><img src="'.base_url().'logos/'.$logo.'" alt = "logo" style="width:20%; height:20%;padding-top:0px;"/></td>
           
            <td align=center width="50%"><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h2 style="color:red; " >'.$reportname.'</h2>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
            </td>
            </tr></table>';

return $content;
}
//$_GET['mem_no'],$_GET['branch_id'],$_GET['name'],$_GET['product'],$_GET['from_year'],$_GET['from_month'],$_GET['from_mday'],$_GET['to_year'],$_GET['to_month'],$_GET['to_mday']
//body
function bodyfun($type,$mem_no,$mem_name,$branch_id,$loan,$stat){

 $branch = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
    $mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
    $mem_name1 = ($mem_name == 'All') ? "" : $mem_name;
  if (strtolower($type) == 'all'){
        $mem = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member  where (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc");
        if($loan == 0){
        $memb = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member  where (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc ");
 $head = "CUSTOMERS LIST";
}elseif($loan == 1){
$memb = @mysql_query("select DISTINCT l.mem_id, m.id, m.mem_no, m.first_name, m.last_name, m.sex, (DATEDIFF(NOW(), dob)/365) as age, m.kin, m.kin_telno, m.address, m.status, m.email, m.telno from member m join loan_applic l on (m.id = l.mem_id) where (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc ");
 $head = "CUSTOMERS WITH LOANS";
}elseif($loan == 2){
$memb = @mysql_query("select m.id, m.mem_no, m.first_name, m.last_name, m.sex, (DATEDIFF(NOW(), dob)/365) as age, m.kin, m.kin_telno, m.address, m.status, m.email, m.telno from member m WHERE NOT EXISTS (SELECT l.mem_id FROM loan_applic l WHERE m.id = l.mem_id) order by first_name, last_name asc ");
 $head = "CUSTOMERS WITHOUT LOANS";
}
    }elseif (strtolower($type) == 'members'){
        $mem = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno  from member where id in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc");
        $memb = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno  from member where id in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc ");
        $head = "MEMBERS LIST";
    }elseif (strtolower($type) == 'non_members'){
        $mem = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member where id NOT in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc");
        $memb = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member where id NOT in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc ");
        $head = "NON-MEMBERS LIST";
    }
    $content = '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th>No</th>
                   <th  width=15% align=left>Name</th>
                    <th width=10% align=left>Member No.</th>
                    <th  width=5% align=left>Age</th>
                    <th  width=5% align=left>Sex</th>
                   <th width=25% align=left>Email</th>
                   <th align=left>Phone</th>
                   <th width=20% align=left>Physical Address</th>
                     <th  width=20% align=left>Next of Kin</th>
                   <th  width=20% align=left>Kin Tel No</th> </tr>
    <tr><td colspan="10"><hr></td></tr>';
$i=1;
        while ($members = @mysql_fetch_array($memb))
        {
            ////$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
                   <tr>
                        <td>".$i."</td>
                        <td>".$members['first_name']."". $members['last_name']."</td>
                         <td>".$members['mem_no']."</td>
                        <td>".floor($members['age'])."</td>
                        <td>".$members['sex']."</td>
                       <td>".$members['email']."</td>
                       <td>".$members['telno']."</td>
                       <td>".$members['address']."</td>
                        <td>".$members['kin']."</td>
                       <td>".$members['kin_telno']."</td>
                   </tr>";
                    $i++;
        }

    $content .="</table>";
return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 
//$mpdf->showImageErrors = true;
$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun($_GET['loan']).'<hr>
';
$headerE = '

';

$footer = '';

$footerE = '';
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
//$mpdf->SetHTMLFooter($footer);
//$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($_GET['type'],$_GET['mem_no'],$_GET['mem_name'],$_GET['branch_id'],$_GET['loan'],$_GET['stat']).'';

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