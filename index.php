<?php
    include_once "common/base.php";
    $pageTitle = "Inicio";
    include_once "common/header.php";
 
    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
?>

<?php
    elseif(!empty($_POST['username']) && !empty($_POST['password'])):
        include_once 'inc/class.users.inc.php';
        $users = new ColoredListsUsers($db);
        if($users->accountLogin()===TRUE):
            echo "<meta http-equiv='refresh' content='0;/'>";
            exit;
        else:
?>
                 
        <h2>Acceso Fallido&mdash;¿Desea intenar otra vez?</h2>
        <form method="post" action="login.php" name="loginform" id="loginform">
            <div>
                <input type="text" name="username" id="username" />
                <label for="username">Usuario</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Contraseña</label>
                <br /><br />
                <input type="submit" name="login" id="login" value="Login" class="button" />
            </div>
        </form><br /><br />
        <p><a href="/password.php">¿Ha olvidado su contraseña?</a></p>
<?php
        endif;
    else:
?>
               
        <h2>Inicia sesión para comenzar</h2>
        <form method="post" action="login.php" name="loginform" id="loginform">
            <div>
                <input type="text" name="username" id="username" />
                <label for="username">Usuario</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Contraseña</label>
                <br /><br />
                <input type="submit" name="login" id="login" value="Login" class="button" />
            </div>
        </form><br />
        <p><a href="/password.php">¿Ha olvidado su contraseña?</a></p>
        
        <hr>
<?php
    endif;
?>

<?php
    if(!empty($_POST['username']) && !empty($_POST['accesskey'])):
        include_once "inc/class.users.inc.php";
        $users = new ColoredListsUsers($db);
        echo $users->createAccount();
    else:
?>
        <h2>Regístrate si aún no tienes cuenta</h2>
        <form method="post" action="signup.php" id="registerform">
            <div>
                <input type="text" name="email" id="email" />
                <label for="email">Email</label>
                <br /><br />
                <input type="text" name="user" id="user" />
                <label for="user">Usuario</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Contraseña</label>
                <br /><br />
                <input type="text" name="accesskey" id="accesskey" />
                <label for="accesskey">Código de acceso</label><br /><br />
                <input type="submit" name="register" id="register" value="Sign up" class="button"/>
            </div>
        </form>
<?php
    endif;
?>
 
        <div style="clear: both;"></div>

<?php include_once "common/footer.php"; ?>
