<?php
/*@filename clientDatabase.php
 *@date 2015-02-28
 *@author Noah Nambale
 *@TODO
 *create new client database
 *create default user for client
 */
 //db: db/mobis_cleanDB_structure.sql
 require_once("conf.php");
 class clientDatabase{
 
 	public static function connect(){
 		return mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
 	}
 	
 	public static function createNewDB($dbName){
 		$dbName = DB_PREFIX.$dbName;
		if(mysql_query("CREATE DATABASE ".$dbName))
			;//echo 'created DB';
		else
			die ('no database created. '.mysql_error());
 	}
 	
 	public static function updateDBs($dbName){
 		$dbName = DB_PREFIX.$dbName;
 		clientDatabase::uploadNewDB($dbName, "db/mobis_cleanDB_structure.sql");
 		clientDatabase::uploadNewDB($dbName, "db/menus.sql");
 		//clientDatabase::uploadNewDB($dbName, "db/users.sql");
 		clientDatabase::uploadNewDB($dbName, "db/userGroups.sql");
 		clientDatabase::uploadNewDB($dbName, "db/userGroupRights.sql");
 		clientDatabase::uploadNewDB($dbName, "db/provissions.sql");
 		clientDatabase::uploadNewDB($dbName, "db/branch.sql");
 		clientDatabase::uploadNewDB($dbName, "db/accounts.sql");
 		clientDatabase::uploadNewDB($dbName, "db/roles.sql");
 		//clientDatabase::uploadNewDB($dbName, "db/company.sql");
 		
 		//echo('updated database');
 	}
 	
 	public static function uploadNewDB($dbName, $filePath){
 		
 		 exec("mysql -u".DB_USER." -p".DB_PASSWORD." ".$dbName." < ".$filePath);
 	}
 	
 	public static function createUser($dbName, $companyName){
 		$dbName = DB_PREFIX.$dbName;
 		
 		$q =mysql_query("INSERT INTO ".$dbName.".users SELECT * FROM ".DB_NAME.".users WHERE companyId=(SELECT companyId FROM ".DB_NAME.".company WHERE companyName='".$companyName."')");
 		if(!$q)
 			die(mysql_error());
 		
 	}
 	
 	public static function createCompany($dbName, $companyName){
 		$dbName = DB_PREFIX.$dbName;
 		
 		$q = mysql_query("INSERT INTO  ".$dbName.".company SELECT * FROM ".DB_NAME.".company WHERE companyId=(SELECT companyId FROM ".DB_NAME.".company WHERE companyName='".$companyName."')");
 		if(!$q)
 			die(mysql_error());
 		
 	}
 	
 	public function connectToClientDB($dbName=""){
 		$cnx = $this->connect();
 		$dbName = DB_PREFIX.$dbName;
 		mysql_select_db($dbName, $cnx);
 		return $dbName;
 	}
 	
 	public function closeClientDB(){
 		return mysql_close($this->conncetToClientDB());
 	}
 }
?>
