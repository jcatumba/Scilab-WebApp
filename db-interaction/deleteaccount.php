<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.users.inc.php";
$userObj = new ColoredListsUsers();
 
if(!empty($_POST['user-id']))
{    
    $userObj->deleteAccount();
}
else
{
    header("Location: /account.php");
    exit;
}
 
?>
