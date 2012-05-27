<?php
    if(!empty($_POST['file']) && !empty($_POST['directory'])){
        if($_POST['action']=="crear"){
            shell_exec('touch ../'.$_POST['directory'].'/'.$_POST['file']);
        }
        if($_POST['action']=="borrar"){
            shell_exec('rm ../'.$_POST['directory'].'/'.$_POST['file']);
        }
        if($_POST['action']=="comprimir"){
            shell_exec('zip ../'.$_POST['directory'].'/files.zip ../'.$_POST['directory'].'/'.$_POST['file']);
        }
        if($_POST['action']=="guardar"){
            shell_exec('echo "'.$_POST['file'].'" > ../'.$_POST['directory']);
        }
    }
?>
