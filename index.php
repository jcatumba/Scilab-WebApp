<?php
    include_once "common/base.php";
    $pageTitle = "Inicio";
    include_once "common/header.php";
 
    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
?>
        <meta http-equiv="refresh" content="0;visordearchivos.php">
<?php
    elseif(!empty($_POST['username']) && !empty($_POST['password'])):
        include_once 'inc/class.users.inc.php';
        $users = new ColoredListsUsers($db);
        if($users->accountLogin()===TRUE):
            echo '<meta http-equiv="refresh" content="visordearchivos.php">';
            exit;
        else:
?>
        <meta http-equiv="refresh" content="visordearchivos.php">
<?php
        endif;
    else:
?>
        <h2>Inicia sesión para comenzar</h2>
        <form method="post" action="login.php" name="loginform" id="loginform" class="nofiles" target="_self">
            <div>
                <input type="text" name="username" id="username" />
                <label for="username">Usuario</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Contraseña</label>
                <br /><br />
                <input type="button" name="login" id="login" value="Login" class="button"/>
            </div>
        </form><br />
        <p><a href="/password.php">¿Ha olvidado su contraseña?</a></p>
        <form id="logsage" method="POST" action="http://localhost:8000/login" style="display:none;" target="_blank">
            <div>
                <input type="hidden" id="emailsage" name="email" size="15" />
            </div>
            <div>
                <input type="hidden" id="passwordsage" name="password" size="15" />
            </div>
            <div>
                <button id="signin" type="submit" style="display:none;">Sign in</button>
            </div>
        </form>
        <script>
            $("#login").click(function () {
                var access = $.ajax({
                    url: "login.php",
                    type: "POST",
                    data: {"username": $("#username").attr('value'), "password": $("#password").attr('value')},
                    dataType: "html"
                });
                access.done(function () { location.reload();});
                $("#emailsage").attr('value',$("#username").attr('value'));
                $("#passwordsage").attr('value',$("#password").attr('value'));
                $("#logsage").submit();
            });
        </script>
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
        <form method="post" action="signup.php" id="registerform" class="nofiles">
            <div>
                <input type="email" name="email" id="email" />
                <label for="email">Email</label>
                <span class="info">--> Campo no válido</span>
                <br /><br />
                <input type="text" name="user" id="user" />
                <label for="user">Usuario</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Contraseña</label>
                <br /><br />
                <input type="password" name="passwordr" id="passwordr" />
                <label for="passwordr">Repita la contraseña</label>
                <br /><br />
                <input type="text" pattern="^[0-9a-zA-Z]*$" name="accesskey" id="accesskey" />
                <label for="accesskey">Código de acceso</label><br /><br />
                <input type="submit" name="register" id="register" value="Sign up" class="button"/>
            </div>
        </form>
<?php
    endif;
?>
 
        <div style="clear: both;"></div>

<?php include_once "common/footer.php"; ?>
