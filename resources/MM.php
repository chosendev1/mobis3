<?php
/*@filename: MM.php
 *@date: 2015-03-29
 *@author: noah nambale [namnoah@gmail.com]
 */
 if(!defined("BEYONIC_API"))
 	define("BEYONIC_API", "bfa18bddacbc7d4fb1416a21556589923f4cd329"); //bfa18bddacbc7d4fb1416a21556589923f4cd329
 	//define("BEYONIC_API", "bfa18bdacbc7d4fb1416a21556589923f4cd329");
 require_once("includes/beyonic/lib/Beyonic.php");
class MM{
	
	
	public static function makeDeposit($msisdn,$amount,$saccoId,$mem_account){
		
		
	}
	
	public static function makeWithdraw($msisdn,$amount,$saccoId,$mem_account){
		try{
			Beyonic::setApiVersion("v1");
			Beyonic::setApiKey(BEYONIC_API);
			$paymentValues = array(
			    'phonenumber' => '+'.$msisdn,
			    'amount'      => $amount,
			    'currency'    => 'UGX',
			    'description' => 'deposit',
			    'metadata'    => "{ 'saccoId': '".$saccoId."', 'accountNo': '".$mem_account."' }"
			);
			$resp = Beyonic_Payment::create( $paymentValues );
			$last_resp = Beyonic::$lastResult['httpResponseCode'] . "\n";
			$resp_val = "";
			foreach( $resp as $key => $value )
			  if( is_object( $value ) || is_array( $value ) ) {
			    foreach( $value as $k => $v ){
			      if($key == "last_error") 
			      	$resp_val = $v;
			     }
			  }
			  else
			    $resp_val =  $value;
			  return $last_resp;//$resp_val == null ? $last_resp : $last_resp."\n".$resp_val;
		  }
		  catch(Exception $ex){
		  	return $ex->getMessage();
		  }
	}
	
	
	
	public function checkBalance(){
		
	}
}
?>
