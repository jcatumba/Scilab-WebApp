<?php
    include_once "common/base.php";
    $pageTitle = "Archivos";
    include_once "common/header.php";
 
    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
    $pre="<li><label><input type='checkbox' name='file' value='";
    $middle="' />";
    $post="</label></li>";
?>

    <?php 
        $archivos=shell_exec('FILES=$(ls '.$homedir.');for i in $FILES; do echo "'.$pre.'$i'.$middle.'$i'.$post.'"; done');
    ?>
    <h2>Visor de archivos</h2>
    
    <div id="viewer_toolbar" class="toolbar">
    <ul class="actions">
    <li><a href="javascript:createNewFile('<?php echo $homedir?>')">Nuevo</a></li>
    <li><a href="javascript:location.reload()">Actualizar lista</a></li>
    <li><a href="javascript:openFile('<?php echo $homedir?>')" target="_parent">Abrir en el editor</a></li>
    <li><a href="javascript:deleteFiles('<?php echo $homedir?>')">Eliminar</a></li>
    <li><a href="#">Descargar</a></li>
    </ul>
    </div>
    
    <form action="">
    <ul id="files">
        <?php echo $archivos ?>
    </ul>
    </form>
    
    <script type="text/javascript" src="js/fileviewactions.js"></script>
<?php
    else:
?>

    <h2>No has iniciado sesión</h2>

<?php
    endif;
?>

<?php include_once "common/footer.php"; ?>
