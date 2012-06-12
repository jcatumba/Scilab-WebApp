<?php
    include_once "common/base.php";
    if(isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn']==1):
        $pageTitle = "Cuenta";
        include_once "common/header.php";
 
        if(isset($_GET['email']) && $_GET['email']=="changed")
        {
            echo "<div class='message good'>Your email address "
                . "has been changed.</div>";
        }
        else if(isset($_GET['email']) && $_GET['email']=="failed")
        {
            echo "<div class='message bad'>There was an error "
                . "changing your email address.</div>";
        }
 
        if(isset($_GET['password']) && $_GET['password']=="changed")
        {
            echo "<div class='message good'>Su contraseña "
                . "ha sido cambiada.</div>";
        }
        elseif(isset($_GET['password']) && $_GET['password']=="nomatch")
        {
            echo "<div class='message bad'>Las dos contraseñas "
                . "son distintas. ¡Intente de nuevo!</div>";
        }
 
        if(isset($_GET['delete']) && $_GET['delete']=="failed")
        {
            echo "<div class='message bad'>Hubo un error "
                . "al intentar borrar su cuenta.</div>";
        }
        if(isset($_GET['codigo']) && $_GET['codigo']=="generado") 
        {
            //$code = $_GET['key'];
            //echo "<div class='info'>El código de acceso generado es ".$code."</div>";
        }
?>
 
        <h2>Su cuenta</h2>
        Nombre de usuario: <?php echo $usuario ?><br />
        Directorio de trabajo: <?php echo $homedir?>
        <hr />
        
        <h2>Cambiar Contraseña</h2>
        <form method="post" action="db-interaction/users.php"
            id="change-password-form">
            <div>
                <input type="hidden" name="action"
                    value="changepassword" />
                <input type="hidden" name="user"
                    value="<?php echo $usuario ?>"/>
                <input type="password"
                    name="pass" id="new-password" />
                <label for="password">Nueva Contraseña</label>
                <br /><br />
                <input type="password" name="r"
                    id="repeat-new-password" />
                <label for="password">Repetir Contraseña</label>
                <br /><br />
                <input type="submit" name="change-password-submit"
                    id="change-password-submit" value="Cambiar Contraseña"
                    class="button" />
            </div>
        </form>
        <hr />
        
        <?php 
            if($grupo == "admin"):
            print ""; ?>
        <h2>Crear código de acceso</h2>
        <form method="post" action="db-interaction/users.php" id="getcode">
        <div>
            <input type="hidden" name="action" value="genkey" />
            <input type="submit" name="getcode-submit" value="Generar" class="button"/>
        </div>
        </form>
        <?php if(!empty($_GET['key'])) {
                echo "El código creado es: ".$_GET['key'];
            }
        ?>
        <hr />
        <?php endif; ?>
            
 
        <form method="post" action="db-interaction/deleteaccount.php"
            id="delete-account-form">
            <div>
                <input type="hidden" name="user-id"
                    value="<?php echo $usuario ?>" />
                <input type="hidden" name="user-dir"
                    value="<?php echo $homedir?>" />
                <input type="submit"
                    name="delete-account-submit" id="delete-account-submit"
                    value="¿Borrar cuenta?" class="button"/>
                <br /><br />
                <p class="info">Todos sus archivos serán borrados. 
                Se recomienda que los guarde en un lugar seguro.</p>
            </div>
        </form>
        
        <script>
            $("#delete-account-submit").click(function(event) {
                if(!confirm('¿Está seguro de eliminar su cuenta?')) {
                    event.preventDefault();
                }
            });
            $("#change-password-submit").click(function(event) {
                if(!confirm('¿Está seguro de cambiar su contraseña?')) {
                    event.preventDefault();
                }
            });
        </script>
 
<?php
    else:
        header("Location: /");
        exit;
    endif;
?>
 
<div class="clear"></div>
 
<?php
    include_once "common/footer.php";
?>
