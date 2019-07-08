<?php date_default_timezone_set("Africa/Kampala"); ?>
<?php
  require_once("ussdOperations.php");
  $ussdOps = new ussdOperations();
?>
<?php
   echo $ussdOps->SACCOCodeExists(25); 
// $ussdOps->connectNewDB("25");
  //echo $ussdOps->checkBalance('AD1001');
  //echo $ussdOps->evaluatePin('1235','AD1001');
  //echo $ussdOps->send_sms(1,'test','test','256782157074');
  echo getMemAccountId('ad1001');
  //echo $ussdOps->sendRequest("http://manage.tambula.net");
?>
