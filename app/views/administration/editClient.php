<?php

//echo $client->companyId;

echo '<div class="col-md-6">
                            <!-- Register form -->
                            <form class="panel nm" name="form-register" id="form-register" action="administration/editAccount" method="POST" data-parsley-validate>
                                <ul class="list-table pa15">
                                    <li>
                                        <!-- Alert message -->
                                        <div class="alert alert-warning nm">
                                            <span class="semibold">Note :</span>&nbsp;&nbsp;Please fill all the below field.
                                        </div>
                                        <!--/ Alert message -->
                                    </li>
                                    <li class="text-right" style="width:20px;"><a href="javascript:void(0);"><i class="ico-question-sign fsize16"></i></a></li>
                                </ul>
                                <hr class="nm">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="control-label">Organization Name</label>
                                        <div class="has-icon pull-left">
                                         <input type="hidden" name="companyId" value="'.$client->companyId.'">
                                        <input type="text" class="form-control" name="orgName" value="'.$client->companyName.'" data-parsley-required>
                                            
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Country</label>
                                        <div class="has-icon pull-left">
                                            <select class="form-control" name="country" value="'.$client->country.'" data-parsley-required>';
                                            foreach(libinc::getCountry() as $key => $country){
                                            		$sel = $key == "Uganda" ? "SELECTED" : NULL;
                                            		
								echo'<option value="'.$key.'" '.$sel.'>'.$country.'</option>';                                            		
                                            		
                                            	}
                                            
                                           echo'</select>
                                            
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="control-label">Region</label>
                                        <div class="has-icon pull-left">
                                            <select class="form-control" name="region" value="'.$client->region.'" data-parsley-required>
                                            <option value="central">Central</option>
                                             <option value="estern">Estern</option>
                                              <option value="northern">Northern</option>
                                               <option value="southern">Southern</option>
                                                <option value="western">Western</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">City</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="city" value="'.$client->city.'" data-parsley-required>
                                           
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="control-label">Date Established</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="dateEstablished" id="datepicker4" value="'.$client->dateEstablished.'">
                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Postal Code</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="postalCode" value="'.$client->postalCode.'">
                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Address 1</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="address1" data-parsley-required value="'.$client->address1.'">
                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Address 2</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="address2" value="'.$client->address2.'">
                                           
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label">Telephone</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="telephone" value="'.$client->telephone.'" data-parsley-required>
                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Website</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="wurl" value="'.$client->wurl.'">
                                           
                                        </div>
                                    </div>
                                    </div>
                                     <hr class="nm">
                                      <div class="panel-body">
                                       <p class="semibold text-muted">This is the client account. it\'s also the administrative account for the user portal.</p>
                                    <div class="form-group">
                                        <label class="control-label">Username</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="username" data-parsley-required value="'.$user->userName.'">
                                            <i class="ico-user2 form-control-icon"></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Password</label>
                                        <div class="has-icon pull-left">
                                            <input type="password" class="form-control" name="password" data-parsley-required value="'.$user->password.'">
                                            <i class="ico-key2 form-control-icon"></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Retype Password</label>
                                        <div class="has-icon pull-left">
                                            <input type="password" class="form-control" name="retype-password" data-parsley-equalto="input[name=password]" value="'.$user->password.'">
                                            <i class="ico-asterisk form-control-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <hr class="nm">
                                <div class="panel-body">
                                    <p class="semibold text-muted">To confirm and activate your new account, we will need to send the activation code to your e-mail.</p>
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <div class="has-icon pull-left">
                                            <input type="email" class="form-control" name="email" placeholder="you@mail.com" data-parsley-required value="'.$client->email.'">
                                            <i class="ico-envelop form-control-icon"></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox custom-checkbox">  
                                            <input type="checkbox" name="agree" id="agree" value="1" data-parsley-required>  
                                            <label for="agree">&nbsp;&nbsp;I agree to the <a class="semibold" href="javascript:void(0);">Term Of Services</a></label>   
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox custom-checkbox">  
                                            <input type="checkbox" name="news" id="news" value="1">  
                                            <label for="news">&nbsp;&nbsp;Send me Newsletters.</label>   
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <button type="submit" class="btn btn-block btn-success"><span class="semibold">Update</span></button>
                                </div>
                            </form>
                            <!-- Register form -->
                        </div>
                    </div>
                </div>';

?>
