
                     <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                        <p><h3 class="semibold text-primary mt0 mb5">PERFOMANCE MONITORING TOOL (PMT)</h3></p>                          
                            </div></div>
                 <table width='100%' class='' id=''>                  
                            
 		 <thead>
			<th>DETAILS</th><th>CURRENT PERIOD</th><th>PREVIOUS PERIOD</th><th> % GROWTH</th></thead><tbody>
		  </tr>
		  <td colspan=4><b>Members</b></td>
		   <? foreach($members as $member){?>
		    <tr>
	<td><?=$member->Details?></td><td><?=$member->CurrentPeriod?></td><td><?=$member->PrevousPeriod ?></td><td><?=$member->percentageGrowth?></td>
		    </tr>
		 <?}?>
		 <tr>
	         <td colspan=4><b>Loans</b></td>
		    </tr>
		   <? foreach($loans as $loan){?>
		    <tr>
	    <td><?=$loan->Details?></td><td><?=$loan->CurrentPeriod?></td><td><?=$loan->PrevousPeriod ?></td><td><?=$loan->percentageGrowth?></td>
		    </tr>
		 <?}?>
		 
		  <td colspan=4><b>Deposits</b></td>
		    <? foreach($deposits as $deposit){?>
		    <tr>
	<td><?=$deposit->Details?></td><td><?=$deposit->CurrentPeriod?></td><td><?=$deposit->PrevousPeriod ?></td><td><?=$deposit->percentageGrowth?></td>
		    </tr>
		 <?}?>
		   <td colspan=4><b>Withdrawals</b></td>
		     <? foreach($withdrawals as $withdrawal){?>
		    <tr>
	<td><?=$withdrawal->Details?></td><td><?=$withdrawal->CurrentPeriod?></td><td><?=$withdrawal->PrevousPeriod ?></td><td><?=$withdrawal->percentageGrowth?></td>
		    </tr>
		 <?}?>
                  </tbody></table>
	
