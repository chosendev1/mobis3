<?php
require_once('app/libraries/AfricasTalkingGateway.php');
class libinc{

	public static $trialDate;
	 
	function getItemById($table,$id,$idField,$dispField){
		$ci =& get_instance();
		$query =$ci->db->query("select ".$dispField." from ".$table." where ".$idField."='".$id."' LIMIT 1");
		//return "select ".$dispField." from ".$table." where ".$idField."='".$id."' LIMIT 1";
		if($query->num_rows() > 0){
			foreach($query->result() as $row)
				$displayItem = $row->$dispField;
			return $displayItem;
		}	
			return 0;
	 }

	function getUserGroupRightIds(){
			$ci =& get_instance();
			$sth = $ci->db->query("SELECT * FROM userGroupRights WHERE groupId=?", array(CAP_Session::get("groupId")));
			if($sth->num_rows() > 0)
				return $sth;
			return false;
	}
	
	function getUserRightIds(){
		$ci =& get_instance();
		$sth = $ci->db->query("SELECT * FROM userRights WHERE userId=?", array(CAP_Session::get("userId")));
		if($sth->num_rows() > 0)
			return $sth;
		return false;
	}
	
	function getSubMenus($parentId=0){
			$ci =& get_instance();
			$sth = $parentId==0 ? $ci->db->query("SELECT * FROM menus order by parentId") : $ci->db->query("SELECT * FROM menus WHERE parentId='".$parentId."' order by parentId");
			//return "Error";
			if($sth->num_rows() > 0)
				return $sth;
			return "Error";
	}

	function hasChildren($parentId){
		$ci =& get_instance();
		$sth = $ci->db->query("SELECT * FROM menus WHERE parentId='".$parentId."' order by parentId");
		if($sth->num_rows() > 0)
			return 1;
		return 0;
	}
	
	function getUserMainMenu($userId){
			$ci =& get_instance();
			$ids = libinc::getUserGroupRightIds();
			//if(libinc::getUserRightIds())
			//	$ids = libinc::getUserRightIds();
			$menuIds = '0';
			if($ids === false)
				;
			else{
				foreach($ids->result() as $id)
					$menuIds = $menuIds == '' || $menuIds == '0' ? $id->menuId : $menuIds.','.$id->menuId;
			}
			//return $menuIds;
			$sth = $ci->db->query("SELECT * FROM menus WHERE menuId in(".$menuIds.") AND parentId=0");
			if($sth->num_rows() > 0)
				return $sth;
			return "Error";
	}
	
	function mainMenus(){
			$ci =& get_instance();
			
			$sth = $ci->db->query("SELECT * FROM menus WHERE parentId=0");
			//if($sth->num_rows() > 0)
				return $sth;
			//return "Error";
	}
	
	function getUserSubMenus($userId){
			$ci =& get_instance();
			$ids = libinc::getUserGroupRightIds();
			//if(libinc::getUserRightIds())
			//	$ids = libinc::getUserRightIds();
			$menuIds = '0';
			if($ids === false)
				;
			else{
				foreach($ids->result() as $id)
					$menuIds = $menuIds == '' || $menuIds == '0' ? $id->menuId : $menuIds.','.$id->menuId;
			}
			//return $menuIds;
			$sth = $ci->db->query("SELECT * FROM menus WHERE menuId in(".$menuIds.") AND parentId>0");
			//if($sth->num_rows() > 0)
				return $sth;
			//return 0;
	}
	
	function getCountry($country=""){
		$content = "";
		$countries = array(
		"Afghanistan"  => "Afghanistan ",
                 "Albania"  => "Albania ",
                 "Algeria"  => "Algeria ",
                 "Andorra"  => "Andorra ",
                 "Anguila"  => "Anguila ",
                 "Antarctica"  => "Antarctica ",
                 "Antigua and Barbuda"  => "Antigua and Barbuda ",
                 "Argentina"  => "Argentina ",
                 "Armenia "  => "Armenia  ",
                 "Aruba"  => "Aruba ",
                 "Australia"  => "Australia ",
                 "Austria"  => "Austria ",
                 "Azerbaidjan"  => "Azerbaidjan ",
                 "Bahamas"  => "Bahamas ",
                 "Bahrain"  => "Bahrain ",
                 "Bangladesh"  => "Bangladesh ",
                 "Barbados"  => "Barbados ",
                 "Belarus"  => "Belarus ",
                 "Belgium"  => "Belgium ",
                 "Belize"  => "Belize ",
                 "Bermuda"  => "Bermuda ",
                 "Bhutan"  => "Bhutan ",
                 "Bolivia"  => "Bolivia ",
                 "Bosnia and Herzegovina"  => "Bosnia and Herzegovina ",
                 "Brazil"  => "Brazil ",
                 "Brunei"  => "Brunei ",
                 "Bulgaria"  => "Bulgaria ",
		   "Burundi"  => "Burundi",
                 "Cambodia"  => "Cambodia ",
                 "Canada"  => "Canada ",
                 "Cape Verde"  => "Cape Verde ",
                 "Cayman Islands"  => "Cayman Islands ",
                 "Chile"  => "Chile ",
                 "China"  => "China ",
                 "Christmans Islands"  => "Christmans Islands ",
                 "Cocos Island"  => "Cocos Island ",
                 "Colombia"  => "Colombia ",
                 "Cook Islands"  => "Cook Islands ",
                 "Costa Rica"  => "Costa Rica ",
                 "Croatia"  => "Croatia ",
                 "Cuba"  => "Cuba ",
                 "Cyprus"  => "Cyprus ",
                 "Czech Republic"  => "Czech Republic ",
                 "Denmark"  => "Denmark ",
                 "Dominica"  => "Dominica ",
                 "Dominican Republic"  => "Dominican Republic ",
                 "Ecuador"  => "Ecuador ",
                 "Egypt"  => "Egypt ",
                 "El Salvador"  => "El Salvador ",
                 "Estonia"  => "Estonia ",
                 "Falkland Islands"  => "Falkland Islands ",
                 "Faroe Islands"  => "Faroe Islands ",
                 "Fiji"  => "Fiji ",
                 "Finland"  => "Finland ",
                 "France"  => "France ",
                 "French Guyana"  => "French Guyana ",
                 "French Polynesia"  => "French Polynesia ",
                 "Gabon"  => "Gabon ",
                 "Germany"  => "Germany ",
                 "Gibraltar"  => "Gibraltar ",
                 "Georgia"  => "Georgia ",
                 "Greece"  => "Greece ",
                 "Greenland"  => "Greenland ",
                 "Grenada"  => "Grenada ",
                 "Guadeloupe"  => "Guadeloupe ",
                 "Guatemala"  => "Guatemala ",
                 "Guinea-Bissau"  => "Guinea-Bissau ",
                 "Guinea"  => "Guinea ",
                 "Haiti"  => "Haiti ",
                 "Honduras"  => "Honduras ",
                 "Hong Kong"  => "Hong Kong ",
                 "Hungary"  => "Hungary ",
                 "Iceland"  => "Iceland ",
                 "India"  => "India ",
                 "Indonesia"  => "Indonesia ",
				 "Iran"  => "Iran ",
				 "Iraq"  => "Iraq ",
                 "Ireland"  => "Ireland ",
                 "Israel"  => "Israel ",
                 "Italy"  => "Italy ",
                 "Jamaica"  => "Jamaica ",
                 "Japan"  => "Japan ",
                 "Jordan"  => "Jordan ",
                 "Kazakhstan"  => "Kazakhstan ",
                 "Kenya"  => "Kenya ",
                 "Kiribati "  => "Kiribati  ",
                 "Kuwait"  => "Kuwait ",
                 "Kyrgyzstan"  => "Kyrgyzstan ",
                 "Lao People\'s Democratic Republic"  => "Lao People's 
                Democratic Republic ",
                 "Latvia"  => "Latvia ",
                 "Lebanon"  => "Lebanon ",
                 "Liechtenstein"  => "Liechtenstein ",
                 "Lithuania"  => "Lithuania ",
                 "Luxembourg"  => "Luxembourg ",
                 "Macedonia"  => "Macedonia ",
                 "Madagascar"  => "Madagascar ",
                 "Malawi"  => "Malawi ",
                 "Malaysia "  => "Malaysia  ",
                 "Maldives"  => "Maldives ",
                 "Mali"  => "Mali ",
                 "Malta"  => "Malta ",
                 "Marocco"  => "Marocco ",
                 "Marshall Islands"  => "Marshall Islands ",
                 "Mauritania"  => "Mauritania ",
                 "Mauritius"  => "Mauritius ",
                 "Mexico"  => "Mexico ",
                 "Micronesia"  => "Micronesia ",
                 "Moldavia"  => "Moldavia ",
                 "Monaco"  => "Monaco ",
                 "Mongolia"  => "Mongolia ",
                 "Myanmar"  => "Myanmar ",
                 "Nauru"  => "Nauru ",
                 "Nepal"  => "Nepal ",
                 "Netherlands Antilles"  => "Netherlands Antilles ",
                 "Netherlands"  => "Netherlands ",
                 "New Zealand"  => "New Zealand ",
                 "Niue"  => "Niue ",
                 "North Korea"  => "North Korea ",
                 "Norway"  => "Norway ",
                 "Oman"  => "Oman ",
                 "Pakistan"  => "Pakistan ",
                 "Palau"  => "Palau ",
                 "Panama"  => "Panama ",
                 "Papua New Guinea"  => "Papua New Guinea ",
                 "Paraguay"  => "Paraguay ",
                 "Peru "  => "Peru  ",
                 "Philippines"  => "Philippines ",
                 "Poland"  => "Poland ",
                 "Portugal "  => "Portugal  ",
                 "Puerto Rico"  => "Puerto Rico ",
                 "Qatar"  => "Qatar ",
                 "Republic of Korea Reunion"  => "Republic of Korea Reunion ",
                 "Romania"  => "Romania ",
                 "Russia"  => "Russia ",
		   "Rwanda"  => "Rwanda",
                 "Saint Helena"  => "Saint Helena ",
                 "Saint kitts and nevis"  => "Saint kitts and nevis ",
                 "Saint Lucia"  => "Saint Lucia ",
                 "Samoa"  => "Samoa ",
                 "San Marino"  => "San Marino ",
                 "Saudi Arabia"  => "Saudi Arabia ",
                 "Seychelles"  => "Seychelles ",
                 "Singapore"  => "Singapore ",
                 "Slovakia"  => "Slovakia ",
                 "Slovenia"  => "Slovenia ",
                 "Solomon Islands"  => "Solomon Islands ",
                 "South Africa"  => "South Africa ",
                 "South Sudan"  => "South Sudan",
                 "Spain"  => "Spain ",
                 "Sri Lanka"  => "Sri Lanka ",
                 "St.Pierre and Miquelon"  => "St.Pierre and Miquelon ",
                 "St.Vincent and the Grenadines"  => "St.Vincent and the 
                Grenadines ",
                 "Sudan"	=>	"Sudan",
                 "Sweden"  => "Sweden ",
                 "Switzerland"  => "Switzerland ",
                 "Syria"  => "Syria ",
                 "Taiwan "  => "Taiwan  ",
                 "Tajikistan"  => "Tajikistan ",
                 "Tanzania"	=>	"Tanzania",
                 "Thailand"  => "Thailand ",
                 "Trinidad and Tobago"  => "Trinidad and Tobago ",
                 "Turkey"  => "Turkey ",
                 "Turkmenistan"  => "Turkmenistan ",
                 "Turks and Caicos Islands"  => "Turks and Caicos Islands ",
                 "Uganda"  => "Uganda ",
                 "Ukraine"  => "Ukraine ",
                 "UAE"  => "UAE ",
                 "UK"  => "UK ",
                 "USA"  => "USA ",
                 "Uruguay"  => "Uruguay ",
                 "Uzbekistan"  => "Uzbekistan ",
                 "Vanuatu"  => "Vanuatu ",
                 "Vatican City"  => "Vatican City ",
                 "Vietnam"  => "Vietnam ",
                 "Virgin Islands (GB)"  => "Virgin Islands (GB) ",
                 "Virgin Islands (U.S.) "  => "Virgin Islands (U.S.)  ",
                 "Wallis and Futuna Islands"  => "Wallis and Futuna Islands ",
                 "Yemen"  => "Yemen ",
                 "Yugoslavia"  => "Yugoslavia "	
				);
		
                /*foreach($countrys as $key => $val){
                	$sel = strtolower($country) == strtolower($key) ? "SELECTED" : NULL;
			$content .="<option value='".$key."' ".$sel.">".$val."</option>";
		}
		return $content;*/
		return $countries;	
	}
	
	public function getCountryData($parentId=0){
		$ci =& get_instance();
		$sth = $ci->db->query("Select countryHierachyId,hierachyName from  countrydata where parentId=".$parentId);
		$buf = array();
		if($sth->num_rows() > 0){
			foreach($sth->result() as $res)
				$buf[$res->countryHierachyId] = $res->hierachyName;
		}
		return $buf;
	}
	
	function getTableItems($table,$id,$disp, $itemId=""){
		$ci =& get_instance();
		$sth = $ci->db->query("select $id,$disp from $table");
		$content = array();
		if($sth->num_rows()>0){
			foreach($sth->result() as $res){
				$content[$res->$id] = $res->$disp;
			}
		return $content;
		}	
	}
	
	function genderSelect2($type=""){
		$content = "";
		$types = array(
				"M"  => "Male",
                "F"  => "Female");

		foreach($types as $key => $val){
                	$sel = strtolower($type) == strtolower($key) ? "SELECTED" : NULL;
			$content .="<option value='".$key."' ".$sel.">".$val."</option>";
		}
		return $content;	
	}
	
	function getItem($table,$id,$disp, $itemId=""){
		$ci =& get_instance();
		$co = $ci->db->query("select $id,$disp from $table");
		$content = "";
		if($co->num_rows()>0){
			foreach($co->result() as $row){
				$sel = $itemId == $row->$id ? "SELECTED" : NULL; 
				$content .="<option value='".$row->$id."' ".$sel.">".$row->$disp."</option>";
			}
		return $content;
		}
	
	}
	
	function getGroups($group=""){
		$ci =& get_instance();
		$groups = $ci->db->query("select * from groups");
		$data = "";
		if($groups->num_rows()>0){
			$data = "<option value=''>--Select group --</option>";
			foreach($groups->result() as $row){
				$selected = strtolower($row->group_name) == strtolower($group) ?"SELECTED" : NULL;
				$data = $data=="" ? "<option value='".$row->id."' ".$selected.">".$row->group_name."</option>" :
				$data .= "<option value='".$row->id."' ".$selected.">".$row->group_name."</option>";
			}
		}
		return $data;
	}
	
	function isCompanyUser(){
		$ci =& get_instance();
		CAP_Session::init();
		$co = $ci->db->query("select companyId from users where userId=".CAP_Session::get('userId'));
		if($co->num_rows() > 0)
			 return $co->row()->companyId <> "";
		return false;
	}
	
	function getCompanyId(){
		$ci =& get_instance();
		CAP_Session::init();
		$co = $ci->db->query("select companyId from users where userId='".CAP_Session::get('userId')."'");
		if($co->num_rows() > 0)
			 return $co->row()->companyId;
		return 0;
	}

        function isUserPassword(){
		$ci =& get_instance();
		CAP_Session::init();
		$co = $ci->db->query("select password from ".DB_NAME.".users where userId='".CAP_Session::get('userId')."'");
		if($co->num_rows() > 0)
			 return $co->row()->password;
		return 0;
	}
	
	function getMaxUserId(){
		$ci =& get_instance();
		$uid = $ci->db->query("SELECT max(userId) as userId FROM ".DB_NAME.".users");
		if($uid->num_rows() > 0)
			 return $uid->row()->userId;
		return 0;
	}
	function getRoles(){
		$ci =& get_instance();
		$retArr = array();
		$sth = $ci->db->query("SELECT * FROM roles");
		if($sth->num_rows() > 0){
			foreach($sth->result() as $res)
				$retArr[$res->roleId] = $res->roleName;
		}
		return $retArr;
	}
	
	function getCreditOfficers(){
		$ci =& get_instance();
		$sth = $ci->db->query("SELECT * FROM employees WHERE roleId=(SELECT roleId FROM roles WHERE roleName='Credit Officer')");
		return $sth;
	}
	
	
	function IdentityTypes($type=""){
			$content = "";
			$types = array(
					"Identity"  => "Identity",
					"Passport"  => "Passport",
					"Lessez-passer"  => "Lessez-passer");

			foreach($types as $key => $val){
						$sel = strtolower($type) == strtolower($key) ? "SELECTED" : NULL;
				$content .="<option value='".$key."' ".$sel.">".$val."</option>";
			}
			return $content;	
	}
	
	function sendSMS($phone, $message, $sender=""){
		require_once("resources/includes/lib.inc.php");
		$sender = $sender == "" ? libinc::getItemById("smppsettings",1,"smppsettingsId","smmp_globalSender") : $sender;
		$sms = new sendSMS();
		$sms->setSenderId($sender);
		$sms->setGroupId($gId);
		$sms->setMessage($message);
		if(strlen($phone) >= 9 && preg_match( '/[0-9]/', $phone )){
			if(substr($phone,0,1) == 0)
				$phone = "256".substr($phone,1,strlen($phone)-1);
			elseif(strlen($phone)==9 && substr($phone,0,1) != 0)
				$phone = "256".$phone;
			$sms->addPhone($phone);
		}
		$sms->setUID($_SESSION['sess_id']);
		return $sms->relayXML();
	}
	
       function cash($accId){
       $bal = 0;
       $date= libinc::$trialDate;
       $bank1_res = mysql_query("select * from bank_account where account_id='".$accId."'");
                           
                            if(mysql_numrows($bank1_res) >0){
                                $bank1 = mysql_fetch_array($bank1_res);
                                //DEPOSITS
                                $dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' and date <='".$date."'");
                                $dep = mysql_fetch_array($dep_res);
                                $dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
                                //WITHDRAWALS
                                $with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' and date <='".$date."'");
                                $with = mysql_fetch_array($with_res);
                                $with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
                                //OTHER INCOME
                                $other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' and date <='".$date."' and transaction not in ('Other Charges', 'Loan Processing Fees','Interest','Penalty')");
                                $other = mysql_fetch_array($other_res);
                                $other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
                                //EXPENSES
                                $expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' and date <='".$date."'");
                                $expense = mysql_fetch_array($expense_res);
                                $expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
                                //LOANS PAYABLE
                                $loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' and p.date <= '".$date."'");
                                $loans_payable = mysql_fetch_array($loans_payable);
                                $loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
                                //PAYABLE PAID
                                $payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' and date <='".$date."'");
                                $payable_paid = mysql_fetch_array($payable_paid_res);
                                $payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
                                //RECEIVALE COLLECTED
                                $collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' and date <='".$date."'");
                                $collected = mysql_fetch_array($collected_res);
                                $collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
                                //DISBURSED LOANS
                                $disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' and date <= '".$date."'");
                                $disb = mysql_fetch_array($disb_res);
                                $disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
                                //PAYMENTS
                                $pay_res = mysql_query("select sum(p.princ_amt + p.int_amt + p.other_charges+p.penalty) as amount from payment p join disbursed d on p.disbursement_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank1['id']."'");
                                $pay = mysql_fetch_array($pay_res);
                                $pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
                                //PENALTIES
                                $pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' and p.date <= '".$date."'");
                                $pen = mysql_fetch_array($pen_res);
                                $pen_amt = ($pen['amount']==NULL) ? 0 : $pen['amount'];
                                
                                //SHARES
                                $shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank1['id']."'");
                                $shares = mysql_fetch_array($shares_res); 
                                $shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
                                //RECOVERED
                                $rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' and r.date <= '".$date."'");
                                $rec = mysql_fetch_array($rec_res); 
                                $rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
                                //INVESTMENTS 
                                $invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank1['id']."'");
                                $invest = mysql_fetch_array($invest_res);
                                $invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
                                //DIVIDENDS PAID
                                $div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
                                $div = mysql_fetch_array($div_res);
                                $div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

				//SOLD INVESTMENTS
                                    $soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' and date <= '".$date."'");
                                    $soldinvest = mysql_fetch_array($soldinvest_res);
                                    
                                //FIXED ASSETS 
                                $fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank1['id']."' and date <='".$date."'");
                                $fixed = mysql_fetch_array($fixed_res);
                                $soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank1['id']."' and date <= '".$date."'");
                                $soldasset = mysql_fetch_array($soldasset_res);
                                    
                                //CASH IMPORTED
                                $import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." and date <='".$date."'");
                                $import = mysql_fetch_array($import_res);
                                $import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

                                //CASH EXPORTED
                                $export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." and date <='".$date."'");
                                $export = mysql_fetch_array($export_res);
                                $export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

                                //CAPITAL FUNDS
                                $fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' and date <='".$date."'");
                                $fund = mysql_fetch_array($fund_res);
                                $fund_amt = $fund['amount'];
				
				//NON CASH TRANSACTIONS

                                $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
                                $dr = mysql_fetch_array($dr_res);
                                $dr_amt = $dr['amount'];
                                
                                $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
                                $cr = mysql_fetch_array($cr_res);
                                $cr_amt = $cr['amount'];

                                $bal = $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt + $dr_amt -$cr_amt;
                                
                                //non cash transactions
                                
     
     }
	return $bal;
}
	function loanBalances($accId){
	$date= libinc::$trialDate;
	$bal=0;
	
	 $qry = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$accId."' and d.date <= '".$date."'");
	if(mysql_num_rows($qry) > 0){
	$loan=mysql_fetch_array($qry);	
	
	$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.disbursement_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$accId."'  and pay.date <='".$date."'");

        $pay = mysql_fetch_array($pay_res);
        $loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$accId."'");
        $loss = mysql_fetch_array($loss_res);
        
        // NON CASH
        $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
        $dr = mysql_fetch_array($dr_res);
        $dr_amt = $dr['amount'];
                                
        $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
        $cr = mysql_fetch_array($cr_res);
        $cr_amt = $cr['amount'];
        
        $bal= $loan['amount'] - $pay['amount'] - $loss['amount'] + $dr_amt -$cr_amt;
        }
	return $bal;	
	}
	
	function receivables($accId){
       $date= libinc::$trialDate;
       $bal=0;
       $rec_res = mysql_query("select sum(amount) as amount from receivable where maturity_date <='".$date."' and account_id='".$accId."'");
       if(mysql_num_rows($rec_res) > 0){
                    $rec = mysql_fetch_array($rec_res);
                    if($rec['amount'] != NULL){
                        $col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where c.date <= '".$date."' and account_id='".$accId."'");
                        $col = mysql_fetch_array($col_res);
                        $bal=  $rec['amount'] - $col['amount'];
                    }
                    
        // NON CASH
        $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
        $dr = mysql_fetch_array($dr_res);
        $dr_amt = $dr['amount'];
                                
        $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
        $cr = mysql_fetch_array($cr_res);
        $cr_amt = $cr['amount'];
        $bal=$bal + $dr_amt -$cr_amt;
       }
	return $bal;	
      }
       
       function fixedAssets($accId){
        $sub1_total = 0;
        $depp1_total = 0;
        $bal=0;
        $date= libinc::$trialDate;
                        //$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level4['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
        // NON CASH
        $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
        $dr = mysql_fetch_array($dr_res);
        $dr_amt = $dr['amount'];
                                
        $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
        $cr = mysql_fetch_array($cr_res);
        $cr_amt = $cr['amount'];
        
        $fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$accId."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
         
                        $fixed = mysql_fetch_array($fixed_res);
                        $fix=$fixed['amount'];
            $bal=$fix + $dr_amt -$cr_amt;        
                        return $bal;
        }
        
        function investments($accId){
         $sub2_total=0;
        $date= libinc::$trialDate;
        
         $invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$accId."' and date <= '".$date."' and id not in (select investment_id from sold_invest where date<='".$date."')");       
         $invest = mysql_fetch_array($invest_res);

         $unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$accId."' and date <='".$date."' order by date desc limit 1"));
         $sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$accId."' and date <='".$date."'");
         $sold = mysql_fetch_array($sold_res);
         $sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
         $sub2_total =$invest['amount'] - $sold_amt;       
         
           // NON CASH
        $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
        $dr = mysql_fetch_array($dr_res);
        $dr_amt = $dr['amount'];
                                
        $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
        $cr = mysql_fetch_array($cr_res);
        $cr_amt = $cr['amount'];
         $sub2_total = $sub2_total + $dr_amt -$cr_amt;
                        return $sub2_total ;
        }
        
        function savingsProducts($accId){
         $sub1_total=0;
        $date= libinc::$trialDate;
        $prod_res = mysql_query("select * from savings_product where account_id='".$accId."'");
                        if(mysql_numrows($prod_res) > 0){
                            $prod = mysql_fetch_array($prod_res);
                            $dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
                            $dep = mysql_fetch_array($dep_res);
                            
                            //LOAN DISBURSEMENT
                            $loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join mem_accounts m on d.mode=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
			    $loans = mysql_fetch_array($loan_res);
			    $loan_amt = ($loans['amount'] > 0) ? $loans['amount'] : 0;
			    
                            $with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
                            $with = mysql_fetch_array($with_res);
                            $int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
                            $int = mysql_fetch_array($int_res);
                            $int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
                            //OTHER DEDUCTIONS
                            $income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.date <= '".$date."'"));
                            $income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
                            
                            $payable = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from payable o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.date <= '".$date."'"));
                            $payable_amt = ($payable['amount'] > 0) ? $payable['amount'] : 0;                           
                           
                            //MONTHLY ACCOUNT CHARGES
                            $charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
                            $charge = mysql_fetch_array($charge_res);
                            //OFFSETTING FROM SAVINGS
                            $pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
                            $pay = mysql_fetch_array($pay_res);
                            $pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
                            
                            //SHARES OFFSET FROM SAVINGS
                            $share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
                            $share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

                            $charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
                            $dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
                            $with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
                            
                            //NON CASH TRANSACTIONS
                            $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$prod['id']."' and date <='".$date."'");
				 $dr = mysql_fetch_array($dr_res);
				 $dr_amt = $dr['amount'];
                                
				$cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$prod['id']."' and date <='".$date."'");
				$cr = mysql_fetch_array($cr_res);
				$cr_amt = $cr['amount']; 
                            
                            $sub1_total = $dep_amt + $loan_amt + $int_amt - $with_amt  - $charge_amt- $pay_amt - $share_amt - $dr_amt + $cr_amt -$income_amt-$payable_amt; 
        
               return $sub1_total;
        }
        }
        
        
        function payables($accId){
        $date= libinc::$trialDate;
        $bal=0;
        $payable= mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.id='".$accId."' and p.date <= '".$date."'");
         if(mysql_num_rows($payable) > 0){
            $row= mysql_fetch_array($payable);
            $bal=$row['amount'];
              
        }
        
         $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
	 $dr = mysql_fetch_array($dr_res);
	 $dr_amt = $dr['amount'];
                                
	 $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
	 $cr = mysql_fetch_array($cr_res);
	 $cr_amt = $cr['amount']; 
	 $bal=$bal- $dr_amt + $cr_amt; 
         return $bal;
        }
                
        
        function nonInterestIncomes($accId,$acctCode){
        $date= libinc::$trialDate;
        $bal=0;
        $income= mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$accId."' and o.date <= '".$date."'");
         if(mysql_num_rows($income) > 0){
            $row= mysql_fetch_array($income);
            $bal=$row['amount'];
              
        }
        
         if($acctCode==4122){
          $dCharge= mysql_query("select sum(percent_value+flat_value) as chargeOnDeposit from deposit where date <= '".$date."'");
         if(mysql_num_rows($dCharge) > 0){
            $row= mysql_fetch_array($dCharge);
            $bal+=$row['chargeOnDeposit'];
              
         }
        
         $wCharge= mysql_query("select sum(percent_value+flat_value) as chargeOnWithdrawal from withdrawal where date <= '".$date."'");
         if(mysql_num_rows($wCharge) > 0){
            $row= mysql_fetch_array($wCharge);
            $bal+=$row['chargeOnWithdrawal'];
              
         }
         }
         $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
	 $dr = mysql_fetch_array($dr_res);
	 $dr_amt = $dr['amount'];
                                
	 $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
	 $cr = mysql_fetch_array($cr_res);
	 $cr_amt = $cr['amount']; 
	 $bal=$bal- $dr_amt + $cr_amt; 
         return $bal;
        }
        
        function interestIncomes($accId){
    
        $date= libinc::$trialDate;
        $bal=0; 
       // $income= mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."'");
        
        $income=mysql_query("select sum(int_amt) as amount from payment where date <= '".$date."'");
        //$income= mysql_query("select sum(amount) as amount from other_income where account_id='".$accId."' and date <='".$date."'");
        if(mysql_num_rows($income) > 0){
            $row= mysql_fetch_array($income);
            $bal=$row['amount'];             
        }
        
        $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
	 $dr = mysql_fetch_array($dr_res);
	 $dr_amt = $dr['amount'];
                                
	 $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
	 $cr = mysql_fetch_array($cr_res);
	 $cr_amt = $cr['amount']; 
	 $bal=$bal- $dr_amt + $cr_amt; 
         return $bal;
        }
        
        function expenses($accId){
         $date= libinc::$trialDate;
        $bal=0; 
        $exp = mysql_query("select sum(amount) as amount from expense where account_id='".$accId."' and date <='".$date."'");
        if(mysql_num_rows($exp) > 0){
            $row= mysql_fetch_array($exp);
            $bal=$row['amount'];             
        }
        
        $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
	 $dr = mysql_fetch_array($dr_res);
	 $dr_amt = $dr['amount'];
                                
	 $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
	 $cr = mysql_fetch_array($cr_res);
	 $cr_amt = $cr['amount']; 
	 $bal=$bal + $dr_amt -$cr_amt; 
         return $bal;
        }
        
        function shares(){
         $date= libinc::$trialDate;
         $share= mysql_query("select sum(value) as value from shares where date <='".$date."'");
         if(mysql_num_rows($share) > 0){
         $shares = mysql_fetch_array($share);
         $bal = $shares['value'];
         if(empty($bal))
         $bal=0;
         } 
         return $bal;
        }
        
        function changesInShares($date1,$date2){
         $bal =0;
         $share= mysql_query("select sum(value) as value from shares where date >='".$date1."' and date <='".$date2."'");
         if(mysql_num_rows($share) > 0){
         $shares = mysql_fetch_array($share);
         $bal = $shares['value'];
         if(empty($bal))
         $bal=0;
         }   
         return $bal;
        }
        
        function institutionalCapital($accId){
    
        $date= libinc::$trialDate;
        $bal=0; 
       
         $cap = mysql_query("select sum(amount) as amount from other_funds where account_id='".$accId."' and date <= '".$date."'  and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
        if(mysql_num_rows($cap) > 0){
            $row= mysql_fetch_array($cap);
            $bal=$row['amount'];             
        } 
        
        $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date <='".$date."'");
	 $dr = mysql_fetch_array($dr_res);
	 $dr_amt = $dr['amount'];
                                
	 $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date <='".$date."'");
	 $cr = mysql_fetch_array($cr_res);
	 $cr_amt = $cr['amount']; 
	 $bal=$bal- $dr_amt + $cr_amt; 
         return $bal;
        }
        
        function changesInCapital($accId,$date1,$date2){
        $bal=0; 
       
         $cap = mysql_query("select sum(amount) as amount from other_funds where account_id='".$accId."' and date >='".$date1."' and date <='".$date2."'  and id not in (select fund_id from payable where and date >='".$date1."' and date <='".$date2."' and fund_id<>'0')");
        if(mysql_num_rows($cap) > 0){
            $row= mysql_fetch_array($cap);
            $bal=$row['amount'];             
        } 
        
        $dr_res= mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."' and date >='".$date1."' and date <='".$date2."'");
	 $dr = mysql_fetch_array($dr_res);
	 $dr_amt = $dr['amount'];
                                
	 $cr_res= mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."' and date >='".$date1."' and date <='".$date2."'");
	 $cr = mysql_fetch_array($cr_res);
	 $cr_amt = $cr['amount']; 
	 $bal=$bal- $dr_amt + $cr_amt; 
         return $bal;
        }
        
function principalPaid($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $applic_id =$loan_id;
		
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
      $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
      $loan = @mysql_fetch_array($sth);
		
      $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where approval_id=".$approval['id']." and date <='".$date."' order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where disbursement_id=".$disb['id']." and date <='".$date."' order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	
	$disburse = mysql_query("select amount,date from disbursed where id='".$disb['id']."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where disbursement_id=".$disb['id']." order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 $date = strtotime($date);
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($date-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($date-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 	 
	 return $principalPaid;
	 }
	 
function interestPaid($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $applic_id =$loan_id;
		
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
      $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
      $loan = @mysql_fetch_array($sth);
		
      $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where approval_id=".$approval['id']." and date <='".$date."' order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where disbursement_id=".$disb['id']." and date <='".$date."' order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	
	$disburse = mysql_query("select amount,date from disbursed where id='".$disb['id']."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where disbursement_id=".$disb['id']." order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 $date = strtotime($date);
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($date-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($date-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 	 
	 return $interestPaid;
	 }
	 

function principalPaidInPeriod($loan_id,$frm_date,$to_date){
 		
         $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
         $approval=mysql_fetch_array($qry2);

         $qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
         $disb=mysql_fetch_array($qry3);
			
	 $totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid from payment where disbursement_id=".$disb['id']." and date <='".$frm_date."' and date <='".$to_date."' order by date asc");						
	 $PY = mysql_fetch_array($totalPY);
		
	 $principalPaid=$PY['total_principal_paid'];
	 
	 return $principalPaid;
	 }
	 
function interestPaidInPeriod($loan_id,$frm_date,$to_date){
         $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
         $approval=mysql_fetch_array($qry2);

         $qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
         $disb=mysql_fetch_array($qry3);
			
	 $totalPY = mysql_query("select SUM(int_amt) as total_interest_paid from payment where disbursement_id=".$disb['id']." and date <='".$frm_date."' and date <='".$to_date."' order by date asc");						
	 $PY = mysql_fetch_array($totalPY);
		
	 $interestPaid=$PY['total_interest_paid'];
	 	 
	 return $interestPaid;
	 }
	 
function penaltyPaidInPeriod($loan_id,$frm_date,$to_date){
         $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
         $approval=mysql_fetch_array($qry2);

         $qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
         $disb=mysql_fetch_array($qry3);
			
	 $totalPY = mysql_query("select SUM(penalty) as total_penalty_paid from payment where disbursement_id=".$disb['id']." and date <='".$frm_date."' and date <='".$to_date."' order by date asc");						
	 $PY = mysql_fetch_array($totalPY);
		
	 $interestPaid=$PY['total_penalty_paid'];
	 	 
	 return $penaltyPaid;
	 }
	 
function principalDue($loan_id,$date){

       $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
	
	$princ = mysql_query("select princ_amt from schedule where approval_id=".$approval['id']." and date ='".$date."'");
	if(mysql_num_rows($princ) >0){
	$row= mysql_fetch_array($princ);
	$principalDue=$row['princ_amt'];
	}
	else $principalDue=0;					
	 return $principalDue;
	 }

function principalToBePaid($loan_id,$date){

       $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
	
	$princ = mysql_query("select sum(princ_amt) as princ_amt  from schedule where approval_id=".$approval['id']." and date <='".$date."'");
	if(mysql_num_rows($princ) >0){
	$row= mysql_fetch_array($princ);
	$principalToBePaid=$row['princ_amt'];
	}
	else $principalToBePaid=0;					
	 return $principalToBePaid;
	}
	
function interestToBePaid($loan_id,$date){

       $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
	
	$int = mysql_query("select sum(int_amt) as int_amt from schedule where approval_id=".$approval['id']." and date <='".$date."'");
	if(mysql_num_rows($int) >0){
	$row= mysql_fetch_array($int);
	$interestToBePaid=$row['int_amt'];
	}
	else $interestToBePaid=0;					
	 return $interestToBePaid;
	}
	 	 
function interestDue($loan_id,$date){
        
        $date1=$date;
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate,p.int_method as int_method,p.int_rate as int_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$loan_id."'");	
	$applic = mysql_fetch_array($applic_res);

        $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
        $approval=mysql_fetch_array($qry2);

	$int = mysql_query("select int_amt from schedule where approval_id=".$approval['id']." and date ='".$date."'");
	if(mysql_num_rows($int)){
	$row= mysql_fetch_array($int);
	$interestDue=$row['int_amt'];
	}
	else $interestDue=0;
	if($applic['int_method']=='Declining Balance'){
	 $principalBal=libinc::loanBalance($loan_id,$date1);
	 $interestDue=libinc::computeDecliningInterest($principalBal,$applic['int_rate']);	 
	 }
	 return $interestDue;
	 }

 function principalDueInPeriod($loan_id,$fdate,$tdate){

       $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
	
	$princ = mysql_query("select sum(princ_amt) as princ_amt from schedule where approval_id=".$approval['id']." and (date >='".$fdate."' and <='".$tdate."')");
	if(mysql_num_rows($princ)){
	$row= mysql_fetch_array($princ);
	$principalDue=$row['princ_amt'];
	}
	else $principalDue=0;					
	 return $principalDue;
	 }
	 
function interestDueInPeriod($loan_id,$fdate,$tdate){
	$date1=$date;
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate,p.int_method as int_method,p.int_rate as int_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$loan_id."'");	
	$applic = mysql_fetch_array($applic_res);

        $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
        $approval=mysql_fetch_array($qry2);

	$int = mysql_query("select sum(int_amt) as int_amt from schedule where approval_id=".$approval['id']." and (date >='".$fdate."' and <='".$tdate."')");
	if(mysql_num_rows($int)){
	$row= mysql_fetch_array($int);
	$interestDue=$row['int_amt'];
	}
	else $interestDue=0;
	if($applic['int_method']=='Declining Balance'){
	 $principalBal=libinc::loanBalance($loan_id,$date1);
	 $interestDue=libinc::computeDecliningInterest($principalBal,$applic['int_rate']);	 
	 }
	 return $interestDue;
	 }
	 
function penaltyDueInPeriod($loan_id,$fdate,$tdate){

        $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
        $approval=mysql_fetch_array($qry2);

	$int = mysql_query("select sum(int_amt) as int_amt from schedule where approval_id=".$approval['id']." and (date >='".$fdate."' and <='".$tdate."')");
	if(mysql_num_rows($int)){
	$row= mysql_fetch_array($int);
	$interestDue=$row['int_amt'];
	}
	else $interestDue=0;
	 return $interestDue;
	 }
	 
function principalArrears($loan_id,$date){

      $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = @mysql_query("select sum(princ_amt) as total_principal_expected,sum(int_amt) as total_interest_expected from schedule where approval_id=".$approval['id']." and date <='".$date."' order by date asc ");
	$PR = @mysql_fetch_array($totalPR);

	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where disbursement_id=".$disb['id']." and date <='".$date."' order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	
	$disburse = mysql_query("select amount,date from disbursed where id='".$disb['id']."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where disbursement_id=".$disb['id']." order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$loan_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 $date = strtotime($date);
	
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 return $principalArrears;
	 }
	 
function InterestArrears($loan_id,$date){
         
         $date1=$date;	
      $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where approval_id=".$approval['id']." and date <='".$date."' order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where disbursement_id=".$disb['id']." and date <='".$date."' order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	
	$disburse = mysql_query("select amount,date from disbursed where id='".$disb['id']."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where disbursement_id=".$disb['id']." order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate,p.int_method as int_method,p.int_rate as int_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$loan_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 $date = strtotime($date);
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($date-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($date-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($applic['int_method']=='Declining Balance'){
	 $principalBal=libinc::loanBalance($loan_id,$date1);
	 $interestArrears=libinc::computeDecliningInterest($principalBal,$applic['int_rate']);	 
	 }
	 	 
	 return $interestArrears;
	 }
	 
function penaltyDue($loan_id,$date){
		
       $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where approval_id=".$approval['id']." and date <='".$date."' order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where disbursement_id=".$disb['id']." and date <='".$date."' order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	
	$disburse = mysql_query("select amount,date from disbursed where id='".$disb['id']."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where disbursement_id=".$disb['id']." order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 $date = strtotime($date);
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($date-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($date-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 
	 
	 return $penaltyDue;
	 }
	 
	 
function loanBalance($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
      $applic_id =$loan_id;
		
      $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
			
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid from payment where disbursement_id=".$disb['id']." and date <='".$date."' order by date asc");						
	$PY = mysql_fetch_array($totalPY);
		
	$disburse = mysql_query("select amount from disbursed where id='".$disb['id']."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
			
	$loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 
	 return $loanBalance;
	 } 
	 
function interestBalance($loan_id,$date){
	$date1=$date;	
      $qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);
      
      $qry4=mysql_query("select sum(princ_amt) as total_principle, sum(int_amt) as total_interest from schedule where approval_id=".$approval['id']."");
$sch=mysql_fetch_array($qry4);

	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where approval_id=".$approval['id']." and date <='".$date."' order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where disbursement_id=".$disb['id']." and date <='".$date."' order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	
	$disburse = mysql_query("select amount,date from disbursed where id='".$disb['id']."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where disbursement_id=".$disb['id']." order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate,p.int_method as int_method,p.int_rate as int_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$loan_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 $date = strtotime($date);
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($date-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($date-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $intBalance=$sch['total_interest']-$PY['total_interest_paid'];
	 $principalPaid=$PY['total_priloanBalancencipal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 /*if($applic['int_method']=='Declining Balance'){
	 $principalBal=libinc::loanBalance($loan_id,$date1);
	 $intBalance=libinc::computeDecliningInterest($principalBal,$applic['int_rate']);	 
	 }
	  */
	 return $intBalance;
	 }
	 
	 function computeDecliningInterest($balance,$rate){
   
   		//$loan = @mysql_query("select * from approval where id='".$approval_id."'");
		//$row=mysql_fetch_array($loan);
		//$rate=$row['rate']/100;
		$rate=$rate/100;
			        
	        $interest=$balance*$rate;
	        $interest=(ceil($interest/100))*100;        
	        return $interest;   
         }
	 
	 function daysInArrears($loan_id,$date){
	  $daysInArrears=0;
	 $appr = mysql_query("select id from approval where applic_id='".$loan_id."'");	
	 $apprId = mysql_fetch_array($appr);
	 $apprvId=$apprId['id'];
	 $schd= @mysql_query("select date from schedule where approval_id=$apprvId and date <='".$date."'");
	 while($row = mysql_fetch_array($schd)){
	 $arrearsDate= date('Y-m-d', strtotime('+1 day', strtotime($row['date'])));
         $arrDays=strtotime($arrearsDate);
         $dateDays=strtotime($date);
	 if(libinc::principalArrears($loan_id,$arrearsDate) > 0){ 
	 $daysInArrears=($dateDays-$arrDays)/ (60 * 60 * 24);
	 break;	 
	 }	
	 }			 	 	 
	 return ceil($daysInArrears);
	 }
	 
	 function ageingArrears($loan_id,$date,$age){
	 $daysInArrears=0;
	 $appr = mysql_query("select id from approval where applic_id='".$loan_id."'");	
	 $apprId = mysql_fetch_array($appr);
	 $apprvId=$apprId['id'];
	 $schd= @mysql_query("select date,princ_amt from schedule where approval_id=$apprvId and date <='".$date."'");
	 $totalAmtPaid=0;
	 while($row = mysql_fetch_array($schd)){
	 $arrearsDate= date('Y-m-d', strtotime('+1 day', strtotime($row['date'])));
         $arrDays=strtotime($arrearsDate);
         $dateDays=strtotime($date);
         $arrears=libinc::principalArrears($loan_id,$arrearsDate);
	 if($arrears> 0){ 
	 $daysInArrears=($dateDays-$arrDays)/ (60 * 60 * 24);
	 $days=ceil($daysInArrears);
	 if($age==1){
	 if($days>=30)
	// if($arrears > $row['princ_amt'])
	 break;
	 }
	 if($age==2){
	 if($days >31 && $days <=60)
	 break;
	 }	
	 
	 if($age==3){
	 if($days >61 && $days <=90)
	 break;
	 }
	 if($age==4){
	 if($days >91 && $days <=120)
	 break;
	 }
	 
	 if($age==5){
	 if($days >121 && $days <=180)
	 break;
	 }
	 
	 if($age==6){
	 if($days >180)
	 break;
	 }	 
	 	
	 }			 	 	 
	 }	 
	 return $arrears;
	 } 
	 
	 
//sms sending	 

function sendMessage($recipients=null,$message=null){
		
	// Specify your login credentials
	$username   = "mobis";
	$apikey     = "cbe29491aa9c6abb9d2d5c36165928baf3f3805e8527c1d39ef19d4eab23e0d6";

	// Specify the numbers that you want to send to in a comma-separated list
	// Please ensure you include the country code (+256 for Uganda in this case)
	if($recipients==null)
		$recipients =  "+256783738616, +2560700738616, +256779512013";

	// And of course we want our recipients to know what we really do
	if($message==null)
		$message    = "Ensibuuko Test message random ".rand(1,10);

	// Create a new instance of our awesome gateway class
	$gateway    = new AfricasTalkingGateway($username, $apikey);

	// Any gateway error will be captured by our custom Exception class below, 
	// so wrap the call in a try-catch block

	try 
	{ 
	  // Thats it, hit send and we'll take care of the rest. 
	  $results = $gateway->sendMessage($recipients, $message);			
	 
	}
	catch ( AfricasTalkingGatewayException $e )
	{
	 // echo "Encountered an error while sending: ".$e->getMessage();
	  $results[] = null;
	}
	
	
	return $results;
}   

function formatDate($date){

$newDate = date('Y-m-d',strtotime($date));
list($y,$m,$d)=explode("-",$newDate);
   $date=$d.'/'.$m.'/'.$y;
   return $date;
}

function loanStatus($loan_id){
$qry1=mysql_query("select isRequest from loan_applic where id=$loan_id");
$request= mysql_fetch_array($qry1);
if($request['isRequest']==1)
$status='Pending Application';
if($request['isRequest']==2)
$status='Request Cancelled';
if($request['isRequest']==0 || $request['isRequest']==3){
//if isRequest ==3, tis means chap chap request proceeded to application
$qry1=mysql_query("select id from approval where applic_id=$loan_id");
if(mysql_num_rows($qry1)){
$approval= mysql_fetch_array($qry1);
$qry2=mysql_query("select id from disbursed where approval_id='".$approval['id']."'");
if(mysql_num_rows($qry2))
$status='Disbursed';
else
$status='Pending Disbursement';
}
else
$status='Pending Approval';
}

   return $status;
}

function get_savings_bal($mode)
{
				if(empty($mode))
				return 0;
				//ACCOUNT BALANCE
				$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where memaccount_id='".$mode."'");
				$dep = mysql_fetch_array($dep_res);
				$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
				//LOAN DISBURSEMENT
				$dis_res = mysql_query("select sum(amount) as amount from disbursed where mode='".$mode."'");
				$dis = mysql_fetch_array($dis_res);
				$dis_amt = ($dis['amount'] != NULL) ? $dis['amount'] : 0;
				//WITHDRAWALS
				$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where memaccount_id='".$mode."'");
				$with = mysql_fetch_array($with_res);
				$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
				//MONTHLY CHARGES 
				$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where memaccount_id='".$mode."'");
				$charge = mysql_fetch_array($charge_res);
				$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
				//INTEREST AWARDED
				$int = mysql_fetch_array(mysql_query("select sum(amount) as amount from save_interest where memaccount_id='".$mode."'"));
				$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
				//LOAN REPAYMENTS
				$pay_res = mysql_query("select sum(princ_amt + int_amt + penalty + other_charges) as amount from payment where mode='".$mode."'");
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
				//INCOME DEDUCTIONS
				$inc_res = mysql_query("select sum(amount) as amount from other_income where mode='".$mode."' and transaction  not in ('Other Charges', 'Loan Processing Fees','Interest','Penalty')");
				$inc = mysql_fetch_array($inc_res);
				$inc_amount = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
				//EQUITY DEDUCTIONS
				$eq_res = mysql_query("select sum(amount) as amount from other_funds where mode='".$mode."'");
				$eq = mysql_fetch_array($eq_res);
				$eq_amount = ($eq['amount'] != NULL) ? $eq['amount'] : 0;
				//SHARES DEDUCTIONS
				$sha_res = mysql_query("select sum(value) as amount from shares where mode='".$mode."'");
				$sha = mysql_fetch_array($sha_res);
				$sha_amount = ($sha['amount'] != NULL) ? $sha['amount'] : 0;
                                //PAYABLES DEDUCTIONS
				$pyb_res = mysql_query("select sum(amount) as amount from payable where mode='".$mode."' and transaction in ('Health Insurance','Loan Fees Payable')");
				$pyb = mysql_fetch_array($pyb_res);
				$pyb_amount = ($pyb['amount'] != NULL) ? $pyb['amount'] : 0;
				//NON CASH DEBIT
				$debit = mysql_fetch_array(mysql_query("select sum(amount) as amount from non_cash where debitedSavingsAcct= '".$mode."'"));
				$debit_amount=$debit['amount'];
				//NON CASH CREDIT
	                        $credit = mysql_fetch_array(mysql_query("select sum(amount) as amount from non_cash where creditedSavingsAcct= '".$mode."'"));
	                        $credit_amount=$credit['amount'];
	                        
	                        //REDEEMED SHARES
	                        $redeemed=mysql_fetch_array(mysql_query("select sum(amount) as amount from shares_redeemed where savings_account_id= '".$mode."'"));
				$redeemed_amount=$redeemed['amount'];
				
				$balance = $dep_amt + $dis_amt + $int_amt - $with_amt - $charge_amt - $pay_amt - $inc_amount - $sha_amount - $pyb_amount - $debit_amount + $credit_amount - $eq_amount + $redeemed_amount;

		return $balance;
}

public function getLogoDataUri(){
	//.(function_exists('mime_content_type') ? mime_content_type($image) :
		$se = mysql_query("select * from company where companyId='".CAP_Session::get('companyId')."' limit 1");
		$sel = mysql_fetch_assoc($se);
		 $image = base_url().'logos/'.$sel['logo'];
		 $mime = explode('.',$sel['logo']);
	    
		$src ='data:image/'.$mime[1].';base64,'.base64_encode(file_get_contents($image));
		
		//echo '<img src="', $src, '">';
		return $src;
	}

public function hasLoan($memId){
		$ln = mysql_query("select d.amount as amount,a.id as approvalId,a.applic_id as loanId,d.id as disburseId from approval a join disbursed d on a.id=d.approval_id where a.mem_id=$memId");
		if(mysql_num_rows($ln) > 0){
		$row = mysql_fetch_array($ln);
		$ln1=mysql_query("select sum(princ_amt+int_amt) as total_loan_amt from schedule where approval_id=".$row['approvalId']);
                $row1=mysql_fetch_array($ln1);
                $loanAmt=$row1['total_loan_amt'];

	        $totalPay = mysql_query("select sum(princ_amt+int_amt) as total_amt_paid from payment where disbursement_id=".$row['disburseId']);
		$row2=mysql_fetch_array($totalPay);
		$paidAmt=$row2['total_amt_paid'];
		if($loanAmt-$paidAmt > 0)		
		return $row['loanId'];
		else return 0;
		}
		else return 0;
}

public function amountDisbursed($loanId){
		$ln = mysql_query("select d.amount as amount,a.id as approvalId,a.applic_id as loanId,d.id as disburseId from approval a join disbursed d on a.id=d.approval_id where a.applic_id=$loanId");
		if(mysql_num_rows($ln) > 0){
		$row = mysql_fetch_array($ln);		
		return $row['amount'];
		}
		else return 0;
}

public function numShares($memId){
                $shares=0;
		$purchased = mysql_query("select sum(shares) as shares from shares where mem_id=$memId");
		if(mysql_num_rows($purchased) > 0){
		$row1 = mysql_fetch_array($purchased);		
		$shares+=$row1['shares'];
		}
		$inward = @mysql_query("select sum(shares) as shares from share_transfer where to_id = $memId");
		if(mysql_num_rows($inward) > 0){
		$row2 = mysql_fetch_array($inward);		
		$shares+=$row2['shares'];
		}
                $outward = @mysql_query("select sum(shares) as shares from share_transfer where from_id = $memId");
                if(mysql_num_rows($outward) > 0){
		$row3 = mysql_fetch_array($outward);		
		$shares-=$row3['shares'];
		}
                $redeemed = @mysql_query("select sum(num_shares) as shares from shares_redeemed where mem_id = $memId");
                if(mysql_num_rows($redeemed) > 0){
		$row4 = mysql_fetch_array($redeemed);		
		$shares-=$row4['shares'];
		}
    //$div_res = mysql_query("select d.id,  s.date, d.amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=$mem_id and s.bank_account=0 order by s.date asc");
		
		return $shares;
         }

public function memaccountId($memId){	
		$ln = mysql_query("select id,mem_id from mem_accounts where mem_id = '".$memId."'");
		if(mysql_num_rows($ln) > 0){
		$row = mysql_fetch_array($ln);		
		return $row['id'];
		}
		else return 0;
}

public function loanCharges($product_id,$amount){
		
		$chargeFee=0;
                $charges = mysql_query("select * from loan_charges where loan_product_id='".$product_id."'");                                            
                if(mysql_num_rows($charges) > 0){                                                     
                while($row=mysql_fetch_array($charges)){
                if($row['amount'] > 0){ //flat amount
                if($row['based_on_loan'] ==1){
                if($row['less_or_equal'] > 0){
                if($amount <=$row['less_or_equal'])
                   $chargeFee+=$row['amount'];
                }
                elseif($row['above'] > 0){
                if($amount > $row['above'])
                   $chargeFee+=$row['amount'];
                }    
                }
                elseif($row['based_on_loan'] ==0){              
                $chargeFee+=$row['amount'];
                }
                }
                   
		if($row['percentage'] > 0){ //percentage
		
		$qry1=@mysql_query("select sum(int_amt) as totalInterest from schedule where approval_id=$approval_id");
		$totalInt = @mysql_fetch_array($qry1);
		$totalInterest=$totalInt['totalInterest'];
		
		if($row['based_on_loan'] ==1){
                if($row['less_or_equal'] > 0){
                if($amount <=$row['less_or_equal']){
                if($row['charge_interest']){		
		$chargeFee+=($row['percentage']*$amount)/100;
		$chargeFee+=($row['percentage']/100)*$totalInterest;
		}
		else
                $chargeFee+=($row['percentage']*$amount)/100;
                }
                }
                elseif($row['above'] > 0){
                if($amount > $row['above']){
                if($row['charge_interest']){		
		$chargeFee+=($row['percentage']*$amount)/100;
		$chargeFee+=($row['percentage']/100)*$totalInterest;
		}
		else
                $chargeFee+=($row['percentage']*$amount)/100;
                }    
                }
                }
                elseif($row['based_on_loan'] ==0){              
                if($row['charge_interest']){		
		$chargeFee+=($row['percentage']*$amount)/100;
		$chargeFee+=($row['percentage']/100)*$totalInterest;
		}
		else
                $chargeFee+=($row['percentage']*$amount)/100;
                }
		
                }
	        }
                }
                return $chargeFee;
                }
                

public function cashAccountBalance($cashAcctId){
			
			//MIMIMUM BALANCE
			$sth = mysql_query("select account_id,min_balance from bank_account where id='".$cashAcctId."'");
		        $min = mysql_fetch_array($sth);
		        $minimumBalance=$min['min_balance'];
		        $account_id=$min['account_id'];
			
			//DEPOSITS
			$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$cashAcctId."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
			//WITHDRAWALS
			$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$cashAcctId."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
			//OTHER INCOME
			$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$cashAcctId."' and transaction not in ('Other Charges', 'Loan Processing Fees','Interest','Penalty')");
			$other = mysql_fetch_array($other_res);
			$other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
			//$other_amt=0;
			//EXPENSES
			$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$cashAcctId."'");
			$expense = mysql_fetch_array($expense_res);
			$expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
			//LOANS PAYABLE
			$loans_payable = mysql_query("select sum(p.amount) as amount from payable p where  p.bank_account='".$cashAcctId."'");
			$loans_payable = mysql_fetch_array($loans_payable);
			$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
			//PAYABLE PAID
			$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$cashAcctId."'");
			$payable_paid = mysql_fetch_array($payable_paid_res);
			$payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
			//RECEIVALE COLLECTED
			$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$cashAcctId."");
			$collected = mysql_fetch_array($collected_res);
			$collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
			//DISBURSED LOANS
			$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$cashAcctId."'");
			$disb = mysql_fetch_array($disb_res);
			$disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
			//PAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt + p.penalty + p.other_charges) as amount from payment p where p.bank_account='".$cashAcctId."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
											
			//SHARES
			$shares_res = mysql_query("select sum(value) as amount from shares where bank_account='".$cashAcctId."'");
			$shares = mysql_fetch_array($shares_res); 
			$shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
			//RECOVERED
			$rec_res = mysql_query("select sum(r.amount) as amount from recovered r where r.bank_account='".$cashAcctId."'");
			$rec = mysql_fetch_array($rec_res);	
			$rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
			//INVESTMENTS 
			$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where bank_account='".$cashAcctId."'");
			$invest = mysql_fetch_array($invest_res);
			$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
			//DIVIDENDS PAID
			$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where bank_account='".$cashAcctId."'");
			$div = mysql_fetch_array($div_res);
			$div_amt = ($div['total_amount'] != NULL) ? $div['total_amount'] : 0;
								
			//SOLD INVESTMENTS
			$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$cashAcctId."'");
			$soldinvestmt = mysql_fetch_array($soldinvest_res);
			$soldinvest =$soldinvestmt['amount'];
			//FIXED ASSETS 
			$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$cashAcctId."'");
			$fixed = mysql_fetch_array($fixed_res);
			$fixed_assets=$fixed['amount'];
			$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$cashAcctId."'");
			$soldassets = mysql_fetch_array($soldasset_res);
			$soldasset=$soldassets['amount'];						
			//CASH IMPORTED
			$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$cashAcctId."'");
			$import = mysql_fetch_array($import_res);
			$import_amt = $import['amount'];

			//CASH EXPORTED
			$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$cashAcctId."'");
			
			$export = mysql_fetch_array($export_res);
			$export_amt = intval($export['amount']);

			//CAPITAL FUNDS
			$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$cashAcctId."'");
			$fund = mysql_fetch_array($fund_res);
			$fund_amt = $fund['amount'];
			
			//NON CASH DEBIT
			$debit_res = mysql_query("select sum(amount) as amount from non_cash where debit='".$account_id."'");
			$debit = mysql_fetch_array($debit_res);
			$non_cash_debit = $debit['amount'];
			
			//NON CASH CREDIT
			$credit_res = mysql_query("select sum(amount) as amount from non_cash where credit='".$account_id."'");
			$credit = mysql_fetch_array($credit_res);
			$non_cash_credit = $credit['amount'];

			$balance =  $collected_amt + $dep_amt+ $loans_payable + $other_amt - $with_amt - $expense_amt + $import_amt - $export_amt - $payable_paid_amt  - $disb_amt + $pay_amt + $shares_amt + $rec_amt + $soldasset + $invest_amt - $invest_amt - $fixed_assets - $div_amt + $fund_amt + $non_cash_debit - $non_cash_credit;	
			
		        return $balance;
                        }
}
?>
