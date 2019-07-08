<?php
require_once('ussdOperations.php');
$xx = new ussdOperations(); 
echo ($xx->evaluatePin('1234')."<br />");
echo ($xx->checkBalance('1010000003')."<br />");
echo ($xx->doTransfer('1010000003', '1010000007', 50000)."<br />");
?>
