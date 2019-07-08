    <?php         
 //get days for password expiry
 $days = mysql_fetch_array(mysql_query("select * from passwordmgt"));
 $expirydays = $days['expiredays'];
 $wakeup = strtotime($days['waketime']);
  $sleep = strtotime($days['sleeptime']);
//test timing for login

////if(time() <  $wakeup && time() >  $sleep){
 //header("location:".base_url()."dashboard");
//}//else{

//}

 //end testing
$today = date("Y-m-d",time());

$date1=strtotime(CAP_Session::get('startDate'));
$date2=strtotime($today);
$diff=(($date2-$date1)/86400);
//$num = $diff->format('%R%a days');


$curlink = $this->uri->segment(1);
                      //$sublink = "";
                      //if(isset($this->uri->segment(2)))
        $sublink = $this->uri->segment(2);
        $mainm = libinc::getUserMainMenu(CAP_Session::get('userId'));
        $subm =  libinc::getUserSubMenus();
       $s = mysql_fetch_array(mysql_query("select account_balance from bank_account where id='".CAP_Session::get('account_assigned')."'")); 


 ?>


<nav class="navbar navbar-inverse navbar-fixed-top" role = "navigation">
   
   <div class = "navbar-header">
      <button type = "button" class = "navbar-toggle" 
         data-toggle = "collapse" data-target = "#example-navbar-collapse">
         <span class = "sr-only">Toggle navigation</span>
         <span class = "icon-bar"></span>
         <span class = "icon-bar"></span>
         <span class = "icon-bar"></span>
      </button>
    
      <a class = "navbar-brand" style="width:170px; height:40px;padding-top:0px;" href = "#"><img src="<? echo base_url();?>resources/themes/admire/image/logo/logo.png" alt="logo" ></a>
   </div>
   
   <div class = "collapse navbar-collapse" id = "example-navbar-collapse">
  
      <ul class = "nav navbar-nav">

      <li><a href="clientDashboard"><span style="color:#e8e8e8"><span class="icon-home"></span></span></a></li>
          <?php 
                        $isCompanyUser  = libinc::isCompanyUser();
                        foreach($mainm->result() as $m){
                            if($isCompanyUser && $m->menuName == "Administration"){
                                continue;
                            }
                            else{
                            // $class = $m->link == $curlink ? "class='active open'" : NULL;
                             //$class2 = $curlink == $m->link ? "class='submenu collapse in'" : "class='submenu collapse'";
                            ?>
                            
                               <li class="dropdown <?php if(substr(strtolower($m->menuName),0,4) == substr(strtolower($curlink),0,4)){echo "active ";}else{}?>" >                        
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                     <?=$m->menuName?>
                     <span class="caret"></span></a>
                      <ul class="dropdown-menu">    
                         <?php
                                foreach($subm->result() as $aM){
                                
                                  if($aM->parentId == $m->menuId){
                                  ?>
                                     <li>
                                  <a href="<?=$m->link.'/'.$aM->link.'/'.$aM->menuId?>">
                                      <?=$aM->menuName?>
                                  </a>
                                </li>
                                <? }} ?>
                                              
                                
                              
                        </ul>
                        <!--/ END 2nd Level Menu -->
                    </li>
                    <?php
                      }
                      }
                    ?>
                    <li class="dropdown">
       <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span style="color:#000"><span class="icon-user"></span>
  <?= ucwords(CAP_Session::get('username'))?> </span></a>
        <ul class="dropdown-menu dropdown-menu-left">
          <li>  <a id="jDialog_modal_button"><span class="icon-briefcase"></span> My Balance</a></li>
          <li> <a href="user/editAccount/<?=CAP_Session::get('userId')?>"><span class="icon-pencil"></span> Edit Password</a></li>
          <li> <a href="serviceAuth/logout"><span class="icon-off"></span> Logout</a></li>
         
        </ul>
      </li>
      </ul>

                            

<!-- jQuery modal dialog -->
        <div class="dialog" id="jDialog_modal" style="display: none;" title="Account Balance">
           <h1><?php echo number_format($s['account_balance'])." - ".CAP_Session::get('account_assigned'); ?></h1>               
        </div>  

 
   </div>
   
</nav>
<div class="navigation">
    <ul class="main">
            <li><a href="" class="active"></a></li>
         
        </ul></div>
         <div class="container">

    <script type="text/javascript">
//echo difference
var diffence = "<?php echo number_format((0-(0-$diff)),0);?>";
var remainds = "<?php echo number_format($expirydays-$diff,0);?>";
var num = "<?php echo number_format($expirydays,0);?>";
 var userdate = "<?php echo CAP_Session::get('startDate');?>";
//alert("date1 = "+remainds);
    var userpop = "<?php echo CAP_Session::get('userId');?>";
$(document).ready(function() {

            setTimeout(function() {
              if(userdate == '0000-00-00'){
    toastr.options = {
                    //closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 100000
                };
                
                toastr.info('<a href="user/editAccount/'+userpop+'">MOBIS remind you that you Have never reset your password. Reset Now!!</a>', 'Password Reset');
              }else if(remainds >= (0.7*num)){
                toastr.options = {
                    //closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 10000
                };
                
               // toastr.info('<a href="user/editAccount/'+userpop+'">MOBIS remainds you that your password is still new and will expire in '+remainds+' days. You may Reset Anyway.</a>', 'Password Reset');
              }else if(remainds <= (5)  && remainds > 0){
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 1000000
                };

                toastr.warning('<a href="user/editAccount/'+userpop+'">MOBIS reminds you that your password will expire in '+remainds+' days. You may reset it.</a>', 'Password Reset');
              }else {
toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 100000000
                };

                toastr.error('<a href="user/editAccount/'+userpop+'">Your MOBIS password expired '+ diffence +' days Ago. Reset Now!!</a>', 'Password Expired');
              }

            }, 1300);
              });
    </script>
   
    
