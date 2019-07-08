       <div class="widget">
                    
                    <div class="block invoice">
                        
                        <h3><?=$client->companyName?></h3>
                        
                        <span><strong>Date Established:</strong><?=$client->dateEstablished?></span>
                        <!--<span class="date"></span> -->
                        <div class="row-fluid">
                            <div class="span3"></div>
                             <div class="span3"></div>
                              <div class="span4"> <div class="info">
                       
                        
                        <a class="btn" href="configuration/logoForm/999911">Edit Logo</a>
                        
                    </div></div>
                         <div class="span2">               
               
                    <div class="image">
                        <img src="logos/<?=$client->logo?>" class="img-polaroid"/>
                    </div>
                   
             
            </div> </div> <br>
             <div class="userCard">
                        <div class="row-fluid">
                            <div class="span3">
                                <h4>Location</h4>
                                <p><strong>Country:</strong>
                                    <?=$client->country?></p>
                                <p><strong>City:</strong>
                                 <?=$client->city?></p>
                                
                                    <p><strong>Region:</strong>
                                    <?=$client->region?><p>
                                 <p><strong>Address1:</strong>
                                 <?=$client->address1?></p>
                                    <p><strong>Address2:</strong>
                                    <?=$client->address2?><p>   
                            </div>
                            <div class="span3">
                                <h4>Contacts</h4>
                                <p><strong>Website:</strong><?=$client->website?></p>
                                <p><strong>Email:</strong><?=$client->email?></p>                              
                                <p><strong>Telephone:</strong><?=$client->telephone?></p>                               
                            </div>
                            <div class="span3">
                                <h4>Representative</h4>
                                <p><strong>Name:</strong><?=$client->rname?></p>
                                <p><strong>Position:</strong><?=$client->position?></p>                              
                                <p><strong>Phone:</strong><?=$client->rphone?></p>
                         
                            </div>
                            <div class="span3">
                                <h4>Others</h4>
                                <p><strong>Certificate Number:</strong><?=$client->cno?></p>
                                <p><strong>Postal Code:</strong>
                                 <?=$client->postalCode?></p>
                                
                         
                            </div>
                        </div>
                        
                        
                        
                    </div>
                    
                </div>
