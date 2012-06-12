<?php
    include_once "common/base.php";
    $pageTitle = "Reiniciar contraseña";
    include_once "common/header.php";
?>
 
        <h2>Reinicio de contraseña</h2>
        <p>Ingrese el código de acceso y el correo electrónico con
        los que se registró.</p>
 
        <form action="db-interaction/users.php" method="post">
            <div>
                <input type="hidden" name="action"
                    value="resetpassword" />
                <input type="text" name="mail" id="mail" />
                <label for="mail">Email</label><br /><br />
                <input type="text" name="key" id="key" />
                <label for="key">Código de acceso</label><br /><br />
                <input type="submit" name="reset" id="reset"
                    value="Reiniciar contraseña" class="button" />
            </div>
        </form>
<?php
    include_once "common/footer.php";
?>
