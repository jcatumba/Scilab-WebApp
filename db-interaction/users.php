<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.users.inc.php";
$users = new ColoredListsUsers();
 
if(!empty($_POST['action'])
&& isset($_SESSION['LoggedIn'])
&& $_SESSION['LoggedIn']==1)
{    
    switch($_POST['action'])
    {
        case 'changeemail':
            $status = $users->updateEmail() ? "changed" : "failed";
            header("Location: /account.php?email=$status");
            break;
        case 'changepassword':
            $status = $users->updatePassword() ? "changed" : "nomatch";
            header("Location: /account.php?password=$status");
            break;
        case 'deleteaccount':
            $userObj->deleteAccount();
            break;
        case 'genkey':
            if($code = $users->generatecodes()){
                $status = "generado";
            } else {
                $status = "error";
            }
            header("Location: /account.php?codigo=$status&key=$code");
            break;
        default:
            header("Location: /");
            break;
    }
}
elseif($_POST['action']=="resetpassword")
{
    if($resp=$users->resetPassword()===TRUE)
    {
        header("Location: /");
    }
    else
    {
        echo $resp;
    }
    exit;
}
else
{
    header("Location: /");
    exit;
}
 
?>
