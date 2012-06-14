<?php
 
/**
 * Handles user interactions within the app
 *
 * PHP version 5
 *
 * @author Jorge Catumba Ruiz
 * @copyright 2012 Jorge Catumba Ruiz
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 */
 
function RandomString($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE)
    {
    	$source = 'abcdefghijklmnopqrstuvwxyz';
    	if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	if($n==1) $source .= '1234567890';
    	if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
    	if($length>0){
    		$rstr = "";
    		$source = str_split($source,1);
    		for($i=1; $i<=$length; $i++){
    			mt_srand((double)microtime() * 1000000);
    			$num = mt_rand(1,count($source));
    			$rstr .= $source[$num-1];
    		}
    	}
    	return $rstr;
    }

function esperarentrada(&$comando, &$shm_id){
    $shm_size = shmop_size($shm_id);
    $shm_data = shmop_read($shm_id, 0, $shm_size);
    if($comando !== $shm_data){
        return $shm_data;
    } return "";
}

class ColoredListsUsers
{
    /**
     * The database object
     *
     * @var object
     */
    private $_db;
 
    /**
     * Checks for a database object and creates one if none is found
     *
     * @param object $db
     * @return void
     */
    public function __construct($db=NULL)
    {
        if(is_object($db))
        {
            $this->_db = $db;
        }
        else
        {
            $this->_db = new PDO("pgsql:host=".DB_HOST.";dbname=".DB_NAME.";user=".DB_USER.";password=".DB_PASS);
        }
    }
    
    public function accountLogin()
    {
        $sql = "SELECT usuario
                FROM usuarios
                WHERE usuario=:user
                AND password=MD5(:pass)
                LIMIT 1";
        try
        {
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
            $stmt->bindParam(':pass', $_POST['password'], PDO::PARAM_STR);
            $stmt->execute();
            if($stmt->rowCount()==1)
            {
                $_SESSION['Username'] = htmlentities($_POST['username'], ENT_QUOTES);
                $_SESSION['LoggedIn'] = 1;
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        catch(PDOException $e)
        {
            return FALSE;
        }
    }

    public function createAccount()
    {
        if($_POST['password']==$_POST['passwordr']) {
            $email = trim($_POST['email']);
            $key = trim($_POST['accesskey']);
            $user = trim($_POST['user']);
            $pass = trim($_POST['password']);
            $dir = trim("usersfiles/".$user);
            
            $sql1 = "SELECT COUNT(usuario) AS countme FROM usuarios WHERE usuario=:user";
            if($stmt = $this->_db->prepare($sql1)) {
                $stmt->bindParam(":user", $user, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch();
                if($row['countme']!=0) {
                    return "<h2> Error </h2>"
                        . "<p> Lo sentimos, ese nombre de usuario ya está en uso. "
                        . "Por favor intente de nuevo. </p>";
                }
                $stmt->closeCursor();
            }
    
            $sql2 = "SELECT COUNT(email) AS count FROM usuarios WHERE email=:email";
            if($stmt = $this->_db->prepare($sql2)) {
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch();
                if($row['count']!=0) {
                    return "<h2> Error </h2>"
                        . "<p> Lo sentimos, ese correo ya está en uso. "
                        . "Por favor intente de nuevo. </p>";
                }
                $stmt->closeCursor();
            }
    
            $sql = "SELECT COUNT(keyaccess) AS counter FROM usuarios WHERE keyaccess=:akey";
            if($stmt = $this->_db->prepare($sql)) {
                $stmt->bindParam(":akey", $key, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch();
                if($row['counter']!=0) {
                    return "<h2> Error </h2>"
                        . "<p> Lo sentimos, la clave insertada no es válida o ya ha sido usada. "
                        . "Por favor intente de nuevo.</p>";
                }
                $stmt->closeCursor();
            }
            
            $accept = 1;
            $shmkey = "";
            /*$sql = "SELECT COUNT(shmid) AS contador FROM usuarios WHERE shmid=:shmkey";
            while ($accept==1) {
                $shmkey = RandomString(6,FALSE);
                if($stmt = $this->_db->prepare($sql)) {
                    $stmt->bindParam(":akey", $shmkey, PDO::PARAM_STR);
                    $stmt->execute();
                    $row = $stmt->fetch();
                    if($row['contador']==0) {
                        $accept = 0;
                    }
                    $stmt->closeCursor();
                }
            }*/
            
            $sql = "INSERT INTO usuarios (usuario,email,keyaccess,password,homedir,grupo,shmid) VALUES(:user,:email,:ver,MD5(:pass),:homedir,'usuario',:shm)";
            if($stmt = $this->_db->prepare($sql)) {
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":ver", $key, PDO::PARAM_INT);
                $stmt->bindParam(":user", $user, PDO::PARAM_STR);
                $stmt->bindParam(":pass", $pass, PDO::PARAM_STR);
                $stmt->bindParam(":homedir", $dir, PDO::PARAM_STR);
                $stmt->bindParam(":shm", $shmkey, PDO::PARAM_STR);
                $stmt->execute();
                $stmt->closeCursor();
                shell_exec('mkdir '.$dir);
                
                return "<h2> ¡Hecho! </h2>"
                        . "<p> Su cuenta ha sido "
                        . "creada con el nombre de usuario <strong>$user</strong>."
                        . "<p> Para iniciar sesión de click <a href='index.php' onClick='register_user()'>aquí</a></p>"
                        . "<script>\n"
                        ." function register_user(){\n"
                        ."    var request = $.ajax({\n"
                        ."        url: \"http://localhost:8000/register\",\n"
                        ."        crossDomain: true,\n"
                        ."        type: \"POST\",\n"
                        ."        data: {username : \"".$user."\", password : \"".$pass."\", retype_password : \"".$pass."\"},\n"
                        ."        dataType: \"json\"\n"
                        ."    });\n"
                        ."    //alert(request.getAllResponseHeaders());\n"
                        ."    request.done(function() {\n"
                        ."        //alert(\"Se ha creado la cuenta\");\n"
                        ."    });\n"
                        ."    request.fail(function(jqXHR, textStatus) {\n"
                        ."        //alert(\"Solicitud fallida (crear cuenta): \"+textStatus)\n"
                        ."    });\n"
                        ."}\n"
                        . "</script>\n";
            } else {
                return "<h2> Error </h2><p> No se ha podido insertar la "
                    . "información de usuario en la base de datos. </p>";
            }
        } else {
            return "<h2> Error </h2>"
                . "<p><div class='message bad'>Las contraseñas no coinciden.</div></p>";
        }
    }
    
    public function retrieveAccountInfo()
    {
        $sql = "SELECT usuario, homedir, grupo, shmid
                FROM usuarios
                WHERE usuario=:user";
        try
        {
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            $stmt->closeCursor();
            return array($row['usuario'], $row['homedir'], $row['grupo'], $row['shmid']);
        }
        catch(PDOException $e)
        {
            return FALSE;
        }
    }
    
    public function generatecodes()
    {
        $accept = 1;
        $genkey = "";
        $sql = "SELECT COUNT(keyaccess) AS counter FROM accesskeys WHERE keyaccess=:akey";
        while ($accept==1) {
            $genkey = RandomString();
            if($stmt = $this->_db->prepare($sql)) {
                $stmt->bindParam(":akey", $genkey, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch();
                if($row['counter']==0) {
                    $accept = 0;
                }
                $stmt->closeCursor();
            }
        }
        
        $sql = "INSERT INTO accesskeys (keyaccess) VALUES(:genkey)";
        try
        {
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":genkey", $genkey, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            return $genkey;
        }
        catch(PDOException $e)
        {
            return FALSE;
        }
    }
    
    public function updatePassword()
    {
        if(isset($_POST['pass']) && isset($_POST['user'])
        && isset($_POST['r'])
        && $_POST['pass']==$_POST['r'])
        {
            $sql = "UPDATE usuarios SET password = MD5(:pass) WHERE usuario = :user";
            try
            {
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":pass", $_POST['pass'], PDO::PARAM_STR);
                $stmt->bindParam(":user", $_POST['user'], PDO::PARAM_STR);
                $stmt->execute();
                $stmt->closeCursor();
 
                return TRUE;
            }
            catch(PDOException $e)
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }
    
    public function deleteAccount()
    {
        if(isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn']==1)
        {
            // Delete files
            shell_exec("rm -r ../".$_POST['user-dir']);
             
            // Delete the user
            $sql = "DELETE FROM usuarios
                    WHERE usuario=:user";
            try
            {
                $stmt = $this->_db->prepare($sql);
                $stmt->bindParam(":user", $_POST['user-id'], PDO::PARAM_STR);
                $stmt->execute();
                $stmt->closeCursor();
            }
            catch(PDOException $e)
            {
                die($e->getMessage());
            }
 
            // Destroy the user's session and send to a confirmation page
            unset($_SESSION['LoggedIn'], $_SESSION['Username']);
            header("Location: /index.php");
            exit;
        }
        else
        {
            header("Location: /account.php");
            exit;
        }
    }
    
    public function resetPassword()
    {
        $em = md5($_POST['mail']);
        $sql = "SELECT keyaccess FROM usuarios WHERE email=:mail";
        if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":mail", $_POST['mail'], PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch();
            $stmt->closeCursor();
            $v = $data['keyaccess'];
        }
        if($v==$_POST['key']){
            header("Location: /resetpassword.php?v=$v&em=$em");
        }
    }
    
    public function verifyAccount()
    {
        $sql = "SELECT usuario
                FROM usuarios
                WHERE keyaccess=:ver
                AND MD5(email)=:email";
 
        if($stmt = $this->_db->prepare($sql))
        {
            $stmt->bindParam(':ver', $_GET['v'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $_GET['em'], PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            if(isset($row['usuario']))
            {
                // Logs the user in if verification is successful
                $_SESSION['Username'] = $row['usuario'];
                $_SESSION['LoggedIn'] = 1;
            }
            else
            {
                return array(4, "<h2>Error de verificación</h2>\n");
            }
            $stmt->closeCursor();
 
            // No error message is required if verification is successful
            return array(0, NULL);
        }
        else
        {
            return array(2, "<h2>Error</h2>n<p>Error de la base de datos.</p>");
        }
    }    
}
?>
