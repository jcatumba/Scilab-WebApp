<?php
    include_once "common/base.php";
    $pageTitle = "Registro";
    include_once "common/header.php";
 
    if(!empty($_POST['user'])):
        include_once "inc/class.users.inc.php";
        $users = new ColoredListsUsers($db);
        echo $users->createAccount();
    else:
?>
        <h2>Regístrate si aún no tienes cuenta</h2>
        <form method="post" action="signup.php" id="registerform">
            <div>
                <input type="email" name="email" id="email" />
                <label for="email">Email</label>
                <span class="info">&mdash; Campo no válido</span>
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
    include_once 'common/footer.php';
?>
