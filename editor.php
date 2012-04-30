<?php
    include_once "common/base.php";
    $pageTitle = "Editor";
    include_once "common/header.php";
 
    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
?>

<div id="application" class="container">
<div id="toolbar"> </div>
<div id="editor" class="nothing">
%definicion del matlab
function a+b; A=[1 0;3 2] %comentario
"cadena", 'hola' %another comment
if, for, end, while, break, else, elseif, true, false
</div>
<script src="src/ace.js" type="text/javascript" charset="utf-8">
</script>
<script src="src/theme-clouds_midnight.js" type="text/javascript" charset="utf-8">
</script>
<script src="src/mode-matlab.js" type="text/javascript" charset="utf-8">
</script>
<script>
window.onload = function(){
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/clouds_midnight");
    var MatlabScriptMode = require("ace/mode/matlab").Mode;
    editor.getSession().setMode(new MatlabScriptMode());
};
</script></div>

<?php
    else:
?>

    <h2>No has iniciado sesión</h2>

<?php
    endif;
?>

<?php include_once "common/footer.php"; ?>
