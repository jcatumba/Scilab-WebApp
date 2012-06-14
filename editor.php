<?php
    include_once "common/base.php";
    $pageTitle = "Editor";
    include_once "common/header.php";

    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username'])):
?>

<div id="application" class="tabs">
    <input id="tab-1" type="radio" name="radio-set" class="tab-selector-1" checked="checked" />
    <label for="tab-1" class="tab-label-1"><?php echo $_GET['file']?></label>
    <!--<input id="tab-2" type="radio" name="radio-set" class="tab-selector-2" />
    <label for="tab-2" class="tab-label-2">Consola</label>-->
    
    <div class="clear-shadow"></div>
    
    <div class="content">
        <div id="content-1">
            <div id="editor_toolbar" class="toolbar">
                <ul class="actions">
                    <li><a href="javascript:saveFile('<?php echo $homedir . '/' . $_GET['file']?>')">Guardar</a></li>
                    <li><a href="javascript:execute('all')">Ejecutar Todo</a></li>
                    <li><a href="javascript:execute('sel')">Ejecutar Selección</a></li>
                </ul>
            </div><!--editor_toolbar-->
            <div id="editor" class="nothing"></div><!--editor-->
            <script src="src/ace.js" type="text/javascript" charset="utf-8"></script>
            <script src="src/theme-textmate.js" type="text/javascript" charset="utf-8"></script>
            <script src="src/mode-matlab.js" type="text/javascript" charset="utf-8"></script>
            <script>
                window.onload = function(){
                    var EditSession = require('ace/edit_session').EditSession;
                    var Document = require('ace/document').Document;
                    $("#editor").css('font-size','14px');
                    editor = ace.edit("editor");
                    editor.setTheme("ace/theme/textmate");
                    var MatlabScriptMode = require("ace/mode/matlab").Mode;
                <?php
                    if(!empty($_GET['file'])){
                        echo "    $.get(\"".$homedir."/".$_GET['file']."\", function(data){\n\t\t\t"
                            . "var doc = new Document(data);\n\t\t\t"
                            . "var sess = new EditSession(doc);\n\t\t\t"
                            . "sess.setMode(new MatlabScriptMode());\n\t\t\t"
                            . "editor.setSession(sess);\n\t\t"
                            . "    });\n";
                    }
                    if(empty($_GET['file'])){
                        echo "editor.getSession().setValue();\n";
                    }
                ?>
                };
            </script>
        </div><!--content-1-->
        <!--<div id="content-2">
            <div id="consola">
                <pre id="consoleoutput"></pre>
            </div>
        </div><!--content-2-->
    </div><!--content-->
</div><!--application-->

<script type="text/javascript" src="js/editoractions.js"></script>
<script>
$(document).ready(function(){
    //idshm = startProcess('<?php echo $shmid?>');
});
</script>

<?php
    else:
?>

    <h2>No has iniciado sesión</h2>

<?php
    endif;
?>

<?php include_once "common/footer.php"; ?>
