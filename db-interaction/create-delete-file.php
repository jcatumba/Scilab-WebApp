<?php
    if(!empty($_POST['file']) && !empty($_POST['directory'])){
        if($_POST['action']=="crear"){
            shell_exec('touch ../'.$_POST['directory'].'/'.$_POST['file']);
        }
        if($_POST['action']=="borrar"){
            shell_exec('rm ../'.$_POST['directory'].'/'.$_POST['file']);
        }
    }
?>
