
 
 <?php
                         
                         $pendDisAmt=0;
                         $pendAppAmt=0;
                         $qry = mysql_query("SELECT id,amount FROM loan_applic");
                          while($row = mysql_fetch_array($qry)){                        
                           if(libinc::loanStatus($row['id'])=="Pending Disbursement")
                            $pendDisAmt+=$row['amount'];
                          }
                          
                          $qry = mysql_query("SELECT id,amount FROM loan_applic");
                          while($row = mysql_fetch_array($qry)){                        
                           if(libinc::loanStatus($row['id'])=="Pending Approval")
                            $pendAppAmt+=$row['amount'];
                          }
                         
                         
                            $result11 = mysql_query("SELECT sum(amount) as amount FROM deposit") or die(mysql_error());
                            while ($rows11 = mysql_fetch_array($result11)) {
                                ?>
 
                            <?php
                            $result11 = mysql_query("SELECT sum(amount) as amount FROM deposit") or die(mysql_error());
                            while ($rows11 = mysql_fetch_array($result11)) {
                                ?>
                                
                                
                                
                                <?php
                            $result22 = mysql_query("SELECT sum(amount) as amount FROM withdrawal") or die(mysql_error());
                            while ($rows22 = mysql_fetch_array($result22)) {
                                ?>
                                
                                 <?php
                            $result33 = mysql_query("SELECT sum(amount) as amount FROM loan_applic") or die(mysql_error());
                            while ($rows33 = mysql_fetch_array($result33)) {
                            
                            
                                ?>
                                
                                  <?php
                            $result44 = mysql_query("SELECT sum(amount) as amount FROM disbursed") or die(mysql_error());
                            while ($rows44 = mysql_fetch_array($result44)) {
                                ?>
                                
                                <?php
                            $result = mysql_query("SELECT count(id) as id FROM member") or die(mysql_error());
                            while ($rows = mysql_fetch_array($result)) {
                                ?>
                                
                                  <?php
                            $result1 = mysql_query("SELECT count(id) as Female FROM member WHERE sex='F'") or die(mysql_error());
                            while ($rows1 = mysql_fetch_array($result1)) {
                                ?>
                                
                                
                                  <?php
                            $result2 = mysql_query("SELECT count(id) as Male FROM member WHERE sex='M'") or die(mysql_error());
                            while ($rows2 = mysql_fetch_array($result2)) {
                                ?>
 <?php
                            $resulta = mysql_query("SELECT sum(amount) as amount,COUNT(id) did FROM deposit") or die(mysql_error());
                            while ($rowsa = mysql_fetch_array($resulta)) {
                                ?>
                              
                        
                          
                                

 <div class="block-fluid"> 
   

    <div class="row-fluid">
        
        <div class="span2">
          <div class="dashboard-wid-wrap">
            <div class="dashboard-wid-content"> <a href="savings/listOfDeposits"> <i class="dashboard-icons computer_imac_blk"></i> <span class="dasboard-icon-title">Total Deposits</span><?php echo  number_format ($rows11['amount']);  ?> ugx</a> </div>
          </div>
        </div>
        <div class="span2">
          <div class="dashboard-wid-wrap">
            <div class="dashboard-wid-content"> <a href="savings/listOfWithdrawal"> <i class="dashboard-icons cog_2_blk"></i> <span class="dasboard-icon-title">Total Withdraws</span> <?php echo  number_format($rows22['amount']);  ?> ugx</a> </div>
          </div>
        </div>
        <div class="span2">
          <div class="dashboard-wid-wrap">
            <div class="dashboard-wid-content"> <a href="loans/pendingApproval"> <i class="dashboard-icons users_blk"></i> <span class="dasboard-icon-title"> Pending Approvals</span><?php echo  number_format($pendAppAmt);  ?> ugx </a> </div>
          </div>
        </div>        
         <div class="span2">
          <div class="dashboard-wid-wrap">
            <div class="dashboard-wid-content"> <a href="savings/listOfDeposits"> <i class="dashboard-icons computer_imac_blk"></i> <span class="dasboard-icon-title">Pending Disbursements</span><?php echo  number_format($pendDisAmt);  ?> ugx</a> </div>
          </div>
        </div>
        <div class="span2">
          <div class="dashboard-wid-wrap">
            <div class="dashboard-wid-content"> <a href="loans/disbursement"> <i class="dashboard-icons graph_blk"></i> <span class="dasboard-icon-title">Total Disbursements</span> <?php echo  number_format ($rows44['amount']);  ?> ugx</a> </div>
          </div>
        </div>
        
        <div class="span2">
          <div class="dashboard-wid-wrap">
            <div class="dashboard-wid-content"> <a href="customers/listOfNonMembers"> <i class="dashboard-icons-colors customers_sl"></i> <span class="dasboard-icon-title">Total Members</span><?php echo $rows['id']; ?></a> </div>
          </div>
        </div>
        
        
    </div>
   
   
    
   
                <?php
                            $resultc = mysql_query("SELECT COUNT(companyId) as id FROM company") or die(mysql_error());
                            while ($rowsc = mysql_fetch_array($resultc)) {
                                ?>
                                
                              
                <?php
                            $result11 = mysql_query("SELECT COUNT(companyId) as id FROM company") or die(mysql_error());
                            while ($rows11 = mysql_fetch_array($result11)) {
                                ?>
 <!--     
<div class="row">
    <div class="span2">
        <div class="panel panel-default">       
        <div class="panel-heading">
            <div class="">
                <h4 class="widget-title"><span> Stats</span></h4>
            </div>
        </div>
         <?php
                            $resultw = mysql_query("SELECT sum(amount) as amount, COUNT(id) as wid  FROM withdrawal") or die(mysql_error());
                            while ($rowsw = mysql_fetch_array($resultw)) {
                                ?>
                                 
                                
                                 <?php
                            $resultd = mysql_query("SELECT sum(amount) as amount, COUNT(id) as dis FROM disbursed") or die(mysql_error());
                            while ($rowsd = mysql_fetch_array($resultd)) {
                                ?>
                                
                                 <?php
                            $resultl = mysql_query("SELECT sum(amount) as amount, COUNT(id) as apid FROM loan_applic") or die(mysql_error());
                            while ($rowsl = mysql_fetch_array($resultl)) {
                                ?>
                                
                                
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td><strong>Total Deposits : </strong><strong class="text-danger"><?php echo  number_format ($rowsa['amount']);  ?>
                     ugx</strong></td>                  
                </tr>
                
                <tr>
                    <td><strong>Number Of Deposits : </strong><strong class="text-danger"><?php echo  number_format ($rowsa['did']);  ?>
                     </strong></td>                 
                </tr>
                <tr>
                    <td><strong>Total Withdraws : </strong><strong class="text-danger"><?php echo  number_format ($rowsw['amount']);  ?>
                     ugx</strong></td>                  
                </tr>
                <tr>
                    <td><strong>Total Loan Amount Disbursed : </strong><strong class="text-danger"><?php echo  number_format ($rowsd['amount']);  ?>
                     ugx</strong></td>                  
                </tr>
                
                <tr>
                    <td><strong>Pending Loan Amount : </strong><strong class="text-danger"><?php echo  number_format ($rowsl['amount']);  ?>
                     ugx</strong></td>                  
                </tr>
                <tr>
                    <td><strong>Male Members: </strong><strong class="text-danger"><?php echo $rows2['Male']; ?></strong></td>                  
                </tr>
                
                <tr>
                    <td><strong>Female Members: </strong><strong class="text-danger"><?php echo $rows1['Female']; ?></strong></td>
                </tr>
                
                <tr>
                    <td><strong>Total Members: </strong><strong class="text-danger"><?php echo $rows['id']; ?></strong></td>
                </tr>
                
            </table>
        </div>
        </div>
    </div>-->
   <div class="col-md-12">
      <div class="col-md-6">
        <div class="panel panel-default">
        <div class="panel-heading">
          <div class="pull-right"> <form action="" method="get">
        <select name="week" onchange="this.form.submit();">
        <option>Select week</option>
        <option value="0">Current Week</option>
        <option value="1">Previos Week</option>
        </select>
        </form></div> 
                <h4 >Daily Savings</h4>
           
        </div>
        
            <div class="table-responsive">
             
                            <div class="days">
                            <div id="mywrapperdl"></div>
                        <?php echo $days;?>
                            </div>
                       
            </div>
        </div>  
   </div>

         <div class="col-md-6">
 <div class="panel panel-default">
        <div class="panel-heading">
           <div class="pull-right"> <!--<form action="" method="get">
        <select name="week" onchange="this.form.submit();">
        <option>Select week</option>
        <option value="0">Current Week</option>
        <option value="1">Previos Week</option>
        </select>
        </form>--></div>
                <h4>Monthly Member Registeration</h4>
           
        </div>
        
    
            <div class="table-responsive">
                
                            <div class="data1">
                            <div id="mywrapperdl"></div>
                        <?php echo $data1;?>
                            </div>
                       
            </div>
        </div> 

</div>

<div class="col-lg-12 col-md-12"> 
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-right"> 
                <form action="" method="get">
                    <select name="year" onchange="this.form.submit();">
                    <option>Select Year</option>
                    <option value="2015">2015</option>
                    <option value="2014">2014</option>
                    </select>
                </form>
            </div>  
            <h4>Total Loan Amount Disbursed</h4>
        </div>

        <div class="table-responsive">
            <div id="test1">
                <?php echo $test1;?>
            </div>
        </div>
    </div> 
</div>





    

        
        
        
             
       
 
         
                            <?php }}
                            ?>  
        <?php }
                            ?> 
          <?php }
                            ?>  
        
        <?php }
                            ?>  
       <?php }
                            ?>   
                            
                             <?php }
                    ?>
      <?php }
                    ?>   <?php }
                            ?>     
                            <?php }
                            ?>   
                  <?php }
                            ?>  
                            
                             <?php }
                            ?>            
                  <?php }
                            ?>              <?php }
                            ?>     
       
<!--	   
<div class="row">
    <div class="col-lg-6 col-md-6"> 
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-right"> 
                    <form action="" method="get">
                        <select name="year" onchange="this.form.submit();">
                        <option>Filter</option>
                        <option value="1">This Week</option>
                        <option value="2">Last Week</option>
                        <option value="3">Monthly</option>
                        </select>
                    </form>
                </div>  
                <h4>Number of ChapChap Transactions</h4>
            </div>

            <div class="table-responsive">
                <script type="text/javascript">

                var noOfCCTransChart;

                    jQuery(document).ready(function(){

                            noOfCCTransChart = new   Highcharts.Chart( {
        chart: {
            type: 'column',
            renderTo:'noOfCCTransChart'
        },
        xAxis: {
            categories: ['Mon', 'Tues', 'Wed', 'Thurs', 'Fri','Sat','Sun']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Transactions Made'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: 0,
            verticalAlign: 'bottom',
            y:25,
            floating: false,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                }
            }
        },
        series: <?php echo $nocctransdata; ?>
    });
                    });
                </script>
                <div id="noOfCCTransChart">
                    
                </div>
            </div>
        </div> 
    </div>
    <div class="col-lg-6 col-md-6"> 
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="pull-right"> 
                <form action="" method="get">
                    <select name="year" onchange="this.form.submit();">
                    <option>Filter</option>
                    <option value="2015">2015</option>
                    <option value="2014">2014</option>
                    </select>
                </form>
            </div>  
            <h4>ChapChap Transactions by Value</h4>
        </div>

        <div class="table-responsive">
        <script type="text/javascript">
$(function () {
    // Create the chart
  valueCCTransactions= new  Highcharts.Chart( {
        chart: {
            type: 'column',
            renderTo: 'valueCCTransactions'
        },
        title: {
            text: '' 
        },
       
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Total Value of Transactions'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}%'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },

        series: [{
            name: 'Transaction Types',
            colorByPoint: true,
            data: [{
                name: 'Deposits',
                y: 56.33,
                drilldown: 'Deposits'
            }, {
                name: 'Withdrawals',
                y: 24.03,
                drilldown: 'Withdrawals'
            }, {
                name: 'Request for loan',
                y: 10.38,
                drilldown: 'Request for loan'
            }]
        }],
        drilldown: {
            series: [{
                name: 'Deposits',
                id: 'Deposits',
                data: [
                    [
                        'v11.0',
                        24.13
                    ],
                    [
                        'v8.0',
                        17.2
                    ],
                    [
                        'v9.0',
                        8.11
                    ],
                    [
                        'v10.0',
                        5.33
                    ],
                    [
                        'v6.0',
                        1.06
                    ],
                    [
                        'v7.0',
                        0.5
                    ]
                ]
            }, {
                name: 'Withdrawals',
                id: 'Withdrawals',
                data: [
                    [
                        'v40.0',
                        5
                    ],
                    [
                        'v41.0',
                        4.32
                    ],
                    [
                        'v42.0',
                        3.68
                    ],
                    [
                        'v39.0',
                        2.96
                    ],
                    [
                        'v36.0',
                        2.53
                    ],
                    [
                        'v43.0',
                        1.45
                    ],
                    [
                        'v31.0',
                        1.24
                    ],
                    [
                        'v35.0',
                        0.85
                    ],
                    [
                        'v38.0',
                        0.6
                    ],
                    [
                        'v32.0',
                        0.55
                    ],
                    [
                        'v37.0',
                        0.38
                    ],
                    [
                        'v33.0',
                        0.19
                    ],
                    [
                        'v34.0',
                        0.14
                    ],
                    [
                        'v30.0',
                        0.14
                    ]
                ]
            }, {
                name: 'Request for loan',
                id: 'Request for loan',
                data: [
                    [
                        'v35',
                        2.76
                    ],
                    [
                        'v36',
                        2.32
                    ],
                    [
                        'v37',
                        2.31
                    ],
                    [
                        'v34',
                        1.27
                    ],
                    [
                        'v38',
                        1.02
                    ],
                    [
                        'v31',
                        0.33
                    ],
                    [
                        'v33',
                        0.22
                    ],
                    [
                        'v32',
                        0.15
                    ]
                ]
            
            }]
        }
    });
});

        </script>
            <div id="valueCCTransactions">
                
            </div>
        </div>
    </div> 
    </div>
</div>

-->