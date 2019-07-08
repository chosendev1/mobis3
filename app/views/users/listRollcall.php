<div class="col-sm-12">
<div class="widget">
                <div class="head">
                    <div class="icon"><span class="icosg-target1"></span></div>
                    <h2>Rollcall Register</h2>                       
                </div>                
                    <div class="panel-heading">
                        <table class="fpTable borderless table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">Id</th>
                                    <th width="10%">User Id</th>
                                    <th width="20%">Name</th>
                                    <th width="20%">Status</th>
                                    <th width="20%">Reporting Time</th>
                                    <th width="15%">Recorded Time</th>
                                    <th width="15%" class="TAC">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                              <? 
$x = 1;
                              foreach ($list as $key) {
                                $dt = new DateTime($key['date_added']);
                                $date = $dt->format('Y-m-d');
if($date == date("Y-m-d",time()) && trim($key['time']) != ""){
$state = "<span class='label label-success'>Present</span>";
$action = '<a href=""><span class="icon-remove"></span></a>';

}else if($date != date("Y-m-d",time())){
$state = "<span class='label label-important'>Absent</span>"; 
$action = '<a href=""><span class="icon-ok"></span></a>';
}else{
 $state = "<span class='label label-important'>Absent</span>"; 
 $action = '<a href=""><span class="icon-ok"></span></a>';
}
                                ?>
                                
                                <tr>
                                    <td><?php echo $x++; ?></td>
                                    <td><?php echo ucwords($key['userId']); ?></td>
                                    <td><?php echo ucwords($key['userName']); ?></td>
                                    <td><?php echo $state;?></td>
                                    <td><?php echo $key['time']; ?></td>
                                    <td><?php echo $key['date_added']; ?></td>
                                    <td class="TAC">

                                        <? echo $action;?>
                                    </td>

                                </tr>
                                <?php }?>
                                                               
                            </tbody>
                        </table>                    
                    </div> 
            </div>  
             </div>            