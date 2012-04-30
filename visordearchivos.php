<?php
    include_once "common/base.php";
    $pageTitle = "Editor";
    include_once "common/header.php";
 
    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
?>

    <!--Colocar aquí el app de visualización de los archivos-->

<?php
    else:
?>

    <h2>No has iniciado sesión</h2>

<?php
    endif;
?>

<?php include_once "common/footer.php"; ?>
