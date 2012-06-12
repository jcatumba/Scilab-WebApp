<?php
    include_once "common/base.php";
    $pageTitle = "Consola";
    include_once "common/header.php";
 
    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
?>

<div id="consola" class="container">
<form id="comand_form" action="">

</form>
</div>

<?php
    else:
?>

    <h2>No has iniciado sesión</h2>

<?php
    endif;
?>

<?php include_once "common/footer.php"; ?>
