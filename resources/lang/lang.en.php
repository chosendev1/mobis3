<?php
function strings(){
$lang["title"] = "MOBIS";
$lang["noPage"] = "This page does not exist.";
$lang["thePage"] = "The page";
$lang["notExist"] = "does not exist";
$lang["registerGroup"] = "Register User Group";
$lang["userGroups"] = "User Groups";
$lang["groupName"] = "Group Name";
$lang["submit"] = "Submit";
$lang["save"] = "Save";
$lang["saveChanges"] = "Save Changes";
$lang["userRights"] = "User Rights";
$lang["addMenu"] = "Add Menu";
$lang["menuName"] = "Menu Name";
$lang["menuLink"] = "Menu Link";
$lang["menuParent"] = "Parent Menu";
$lang["parentSelect"] = "Select Parent Menu if not root";
$lang["id"] = "Id";
$lang["parent"]	="Parent";
$lang["link"]	="Link";
$lang["permissions"]	="Permissions";
$lang["menu"]	="Menu";
$lang["menus"]	="Menus";
$lang["addUser"]	="Add User";
$lang["userName"]	="Username";
$lang["password"]	="Password";
$lang["oldpassword"]	="Old Password";
$lang["newpassword"]	="New Password";
$lang["confirmPassword"]	="Confirm Password";
$lang["userGroup"] = "User Group";
$lang["userGroupSelect"] = "Select User Group";
$lang["submit"] = "Submit";
$lang["status"] = "Status";
$lang["select status"] = "Select User Status";
$lang["active"] = "Active";
$lang["inactive"] = "Inactive";
$lang["suspended"] = "Suspended";
$lang["pending"] = "Pending";
/*------------------------------------------
	Login form
--------------------------------------------*/


/*----------------------------------------
	accountSettings Form
-----------------------------------------*/

/*=========Account Form ==================*/
$lang['accounts'] = "Register Accounts";
$lang['bankName'] = "Bank Name";
$lang['accountsName'] = "Account Name";
$lang['accountsCode'] = "Account Code";

/*==========bankAccount Form=============*/
$lang['bankAccount'] = "Register Bank Account";
$lang['bankName'] = "Bank Name";
$lang['accountName'] = "Account Name";
$lang['accountNo'] = "Account Number";

/*----------------------------------------
		organisationSettings
-----------------------------------------*/
/*==========organisation==================*/
$lang['organization'] = " Register Organization";
$lang['organizationName'] = "Organization Name";
$lang['physicalAddress'] = "Physical Address";
$lang['postalAddress'] = "Postal Address";
$lang['telephone'] = "Telephone";
$lang['email'] = "Email Address";
$lang['website'] = "Website Url";
$lang['header'] = "Header";
$lang['footer'] = "Footer";

/*------------------------------------------
		organizationStructure
--------------------------------------------*/
$lang['organizationStructure'] = "Register Organization Structure";
$lang['structure'] = "Organization Name";
$lang['parentId'] = "Parent";

/*-------------------------------------------
		organizationLevels
---------------------------------------------*/
$lang['organizationLevels'] = "Register Level";
$lang['levelName'] = "Organization Level";
$lang['description'] = "Description";
$lang['levelDuration'] = "Duration";

/*--------------------------------------------
		teachingRooms
----------------------------------------------*/
$lang['teachingRooms'] = "Register Room";
$lang['roomName'] = "Room Name";
$lang['roomType'] = "Room Type";

/*---------------------------------------------
		houses/halls
-----------------------------------------------*/
$lang['house/hall'] = "Register Residential";
$lang['hallName'] = "House/Hall Name";
$lang['unitName'] = "House/Hall Unit";
	return $lang;
}
//echo count(strings());

?>
