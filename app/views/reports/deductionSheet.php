<?php

$ded_qry = mysql_query("select amount from savingDeductions");
    $row = mysql_fetch_array($ded_qry);
    $contribution =$row['amount'];
    $date=date('Y-m-d');
$memb = mysql_query("select id,mem_no,first_name,last_name from member");

$content = ' <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="text-primary mt0">Deduction Sheet Report As at '.date('d-m-Y').'</h4></p>                           
                            </div>';
         $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-dud').tableExport({type:'excel',escape:'false'});\" value='Excel'>   
   <input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-dud', filename:'customers list.pdf', title:'LIST OF CUSTOMERS', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";                   
                            
        $content .= '<table class="borderless table-hover" id="table-dud" width=100%>';
        $content .= "                
                <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Accout No.</th>
                    <th>Loan Amount</th>
                    <th>Loan Amount Due(P+I+Arrears)</th>
                   <th>Savings Contribution</th>
                   <th>Total Deductions</th>
                </thead><tbody>
                    ";
        $i=1;
        $totalAmtDue=0;
        $totalContribution=0;
        while ($members = @mysql_fetch_array($memb))
        {
            ////$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
                   <tr>
                        <td>".$i."</td>
                        <td>".$members['first_name']." ".$members['last_name']."</td>
                        <td>".$members['mem_no']."</td>";
                        if(libinc::hasLoan($members['id']) > 0){
                         $loanAmt=libinc::amountDisbursed(libinc::hasLoan($members['id']));
                         $content .= "<td>".number_format($loanAmt)."</td>";
                        }
                        else{
                         $loanAmt=0;
                         $content .= "<td>".$loanAmt."</td>";
                         }
                        if(libinc::hasLoan($members['id']) > 0){
                            $loanId=libinc::hasLoan($members['id']);                     
			   $principalDue=libinc::principalDue($loanId,$date);
			   $interestDue=libinc::interestDue($loanId,$date);
			   //$principalPaid=libinc::principalPaid($row['loan_id'],$date);
			   //$InterestPaid=libinc::interestPaid($row['loan_id'],$date);
			   //$outstandingBal=libinc::loanBalance($row['loan_id'],$date);
			   $principalArrears=libinc::principalArrears($loanId,$date);
			   $interestArrears=libinc::interestArrears($loanId,$date);
                           $amtDue=$principalDue+$interestDue+$principalArrears+$interestArrears;
                           $content .= "<td>".number_format($amtDue)."</td>";
                         }
                         else{
                         $amtDue=0;
                         $content .= "<td>".$amtDue."</td>";
                         }
                        
                        $content.="<td>".number_format($contribution)."</td>
                        <td>".number_format($contribution+$amtDue)."</td>
                        
                   </tr>";
                    $i++;
                    $totalLoanAmt+=$loanAmt;
                    $totalAmtDue+=$amtDue;
                    $totalContribution+=$contribution;
        }
                    $content.="<tr><th></th><th>Totals</th><th></th><th>".number_format($totalLoanAmt)."</th><th>".number_format($totalAmtDue)."</th><th>".number_format($totalContribution)."</th><th>".number_format($totalAmtDue+$totalContribution)."</th></tr>";
    $content .= "<tbody></table></div>";
    
    echo $content;
    
    ?>

