<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>M-Voucher</title>

    <?php $this->load->view('components/styles'); ?>
    
 </script>
</head>

<body>
    <div class="container">
 
<?php $this->load->view('components/menu'); ?>

            <div class="wrapper">
        
            <div class="row">
             
             <div class="panel panel-default">
  <div class="panel-body">
                    <h3>Agro Dealers and Agents</h3>
                        
                    <hr>

                        <div class="table-responsive">
                            <table class="table">
                    <thead>
                    <tr>
                  
                        <th>Name</th>  
                        <th>Type</th>                      
                        <th>Phone Number</th>
                        <th>District</th>
                        
                        
                        
                    </tr>
                    </thead>
                    <tbody>
                        <?php 

                        foreach ($dealer_a as $dealer){?>
                    <tr class="innovate1" >
                        <td><?php echo $dealer['dealerName']; ?></td>                        
                       <td><?php echo $dealer['type']; ?></td>
                       <td><?php echo $dealer['dphone_number']; ?></td>
                       <td><?php echo $dealer['district']; ?></td>                      
                    </tr>
                    <?php
                    foreach($dealer['dagents'] as $agent){?>
                        <tr class="gradeX">
                      
                        <td><input type="checkbox">&nbsp;&nbsp;<?php echo $agent['name']; ?></td>                        
                         <td><?php echo $agent['type']; ?></td>
                       <td><?php echo $agent['aphone_number']; ?></td>
                       <td><?php echo $agent['district']; ?></td>
                      
                    </tr>
                    <?php }
                    } ?>
                  
                    </table>
                    
                        </div>

                    </div>
                </div>
            </div>
            </div>

        </div>
       <?php $this->load->view('components/footer'); ?>

        </div>
        </div>



   <?php $this->load->view('components/scripts'); ?>
</body>


<!-- Mirrored from webapplayers.com/inspinia_admin-v2.4/table_data_tables.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 26 Jan 2016 04:26:33 GMT -->
</html>
