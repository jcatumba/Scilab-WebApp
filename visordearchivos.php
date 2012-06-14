<?php
    include_once "common/base.php";
    $pageTitle = "Archivos";
    include_once "common/header.php";
 
    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
    $pre="<li><label><input type='checkbox' name='file' value='";
    $middle="' />";
    $post="</label></li>";
?>
    <script>
    </script>
    <?php
        if(!empty($_GET['event'])){
            if($_GET['event']=="done"){
                echo '<p class="message good">El archivo se ha subido exitosamente</p>';
            }
            if($_GET['event']=="fail"){
                echo '<p class="message bad">Ha ocurrido un error</p>';
            }
            if($_GET['event']=="exists"){
                echo '<p class="message bad">El archivo ya existe</p>';
            }
        }
    ?>

    <?php 
        $archivos=shell_exec('FILES=$(ls '.$homedir.' | sed -e s/.*zip$//);for i in $FILES; do echo "'.$pre.'$i'.$middle.'$i'.$post.'"; done');
    ?>
    <h2>Visor de archivos</h2>
    
    <div id="viewer_toolbar" class="toolbar">
    <ul class="actions">
    <li><a href="javascript:createNewFile('<?php echo $homedir?>')">Nuevo</a></li>
    <li><a href="javascript:location.reload()">Actualizar lista</a></li>
    <li><a href="javascript:openFile('<?php echo $homedir?>')" target="_blank">Abrir en el editor</a></li>
    <li><a href="javascript:deleteFiles('<?php echo $homedir?>')">Eliminar</a></li>
    <li><a href="javascript:downloadFiles('<?php echo $homedir?>')">Descargar</a></li>
    </ul>
    </div>
    
    <form action="">
    <ul id="files">
        <?php echo $archivos ?>
    </ul>
    </form>
    
    <hr />
    <h2>Subir archivos</h2> Podrá subir únicamente scripts de Matlab y de R
    <br /><br />
    <form action="upload_file.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="folder" value="<?php echo $homedir?>" />
        <label for="file">Archivo:</label>
        <input type="file" name="file" id="file" /> 
        <br /><br />
        <input type="submit" name="submit" value="Cargar" class="button" />
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
