<?php
    include_once "common/base.php";
    $pageTitle = "Home";
    include_once "common/header.php";
 
    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
?>
        <meta http-equiv="refresh" content="visordearchivos.php">
<?php
    elseif(!empty($_POST['username']) && !empty($_POST['password'])):
        include_once 'inc/class.users.inc.php';
        $users = new ColoredListsUsers($db);
        if($users->accountLogin()===TRUE):
            echo "<script>\n"
                . "$(document).ready(function()\n"
                . "{\n"
                . "\tvar login = $.ajax({\n"
                . "\t\t url: \"http://localhost:8000/simple/login\",\n"
                . "\t\t type: \"GET\",\n"
                . "\t\t data: {username: ".$_POST['username'].", password: ".$_POST['password']."},\n"
                . "\t\t dataType: \"json\"\n"
                . "\t});\n"
                . "});\n"
                ."</script>";
            header('Location: visordearchivos.php');
            exit;
        else:
?>
                 
        <h2>Acceso fallido &mdash; ¿Desea intentar de nuevo?</h2>
        <form method="post" action="login.php" name="loginform" id="loginform">
            <div>
                <input type="text" name="username" id="username" />
                <label for="username">Email</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Password</label>
                <br /><br />
                <input type="submit" name="login" id="login" value="Login" class="button" />
            </div>
        </form><br /><br />
        <p><a href="/password.php">¿Ha olvidado su contraseña?</a></p>
<?php
        endif;
    else:
?>
               
        <h2>¿Qué espera para acceder?</h2>
        <form method="post" action="login.php" name="loginform" id="loginform">
            <div>
                <input type="text" name="username" id="username" />
                <label for="username">Email</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Password</label>
                <br /><br />
                <input type="submit" name="login" id="login" value="Login" class="button" />
            </div>
        </form><br /><br />
        <p><a href="/password.php">¿Ha olvidado su contraseña?</a></p>
<?php
    endif;
?>
 
        <div style="clear: both;"></div>
<?php
    include_once "common/footer.php";
?>
