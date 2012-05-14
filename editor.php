<?php
    include_once "common/base.php";
    $pageTitle = "Editor";
    include_once "common/header.php";
 
    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
?>

<div id="application" class="">
<div id="editor_toolbar" class="toolbar">
    <ul class="actions">
    <li><a href="javascript:saveFile('<?php echo $homedir?>')">Guardar</a></li>
    <li><a href="#">Ejecutar</a></li>
    </ul>
    </div>
<div id="editor" class="nothing">
</div>
<script src="src/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="src/theme-clouds.js" type="text/javascript" charset="utf-8"></script>
<script src="src/mode-matlab.js" type="text/javascript" charset="utf-8"></script>
<script>
window.onload = function(){
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/clouds");
    $("#editor").css('font-size','14px');
    var MatlabScriptMode = require("ace/mode/matlab").Mode;
    editor.getSession().setMode(new MatlabScriptMode());
    $.get(<?php echo $_GET['file']?>, function(data){
        /*$('#editor').html(data);*/
        editor.getSession().setValue(data);
    });
};

function saveFile(directorio){
    var editor = ace.edit("editor");
    alert(editor.getSession().getValue());
}
</script>
</div>
<script type="text/javascript" src="js/editoractions.js"></script>
<?php
    else:
?>

    <h2>No has iniciado sesión</h2>

<?php
    endif;
?>

<?php include_once "common/footer.php"; ?>
