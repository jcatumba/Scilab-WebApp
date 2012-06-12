<?php
    include_once "../inc/class.users.inc.php";
    ini_set("expect.loguser", "Off");
    global $stream;
    $comando = "";
    $casos = array (
      array("Name", "name"),
      array("Password:", "pass", EXP_REGEXP),
      array("ftp>", "FTP", EXP_REGEXP)
    );
    
    if ($_POST['action'] == "start" ) {
        $stream = expect_popen('ftp ftp.kernel.org');
        /*$stream = expect_popen('export MATLAB_PREFDIR=./.matlab/R2011a/ /usr/local/MATLAB/R2011a/bin/matlab; matlab -nosplash -nodisplay -nodesktop -nojvm');*/
        $shm_id = shmop_open($_POST['shm'], "n", 0644, 10000);
        echo $shm_id;
        while (true) {
            switch (expect_expectl ($stream, $casos)) {
                case "name":
                    fwrite($stream, "anonymous\n");
                    break;
                case "pass":
                    fwrite($stream, "jcatumbar@unal.edu.co\n");
                    break;
                case "FTP":
                    while ($comando !== "quit") {
                        sleep(30);
                        $comando = esperarentrada($comando,$shm_id);
                        fwrite($stream, $comando."\n");
                        echo fgets($stream);
                    }
                    break 2;
                case EXP_EOF:
                    break 2;
                default:
                    die ("Error al iniciar el proceso");
            }
        }
    }
    
    if($_POST['action']=="stop"){
        fclose($stream);
    }
?>
