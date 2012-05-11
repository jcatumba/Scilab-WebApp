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
             
            $sql = "INSERT INTO usuarios (usuario,email,keyaccess,password,homedir,grupo) VALUES(:user,:email,:ver,MD5(:pass),:homedir,'usuario')";
            if($stmt = $this->_db->prepare($sql)) {
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":ver", $key, PDO::PARAM_INT);
                $stmt->bindParam(":user", $user, PDO::PARAM_STR);
                $stmt->bindParam(":pass", $pass, PDO::PARAM_STR);
                $stmt->bindParam(":homedir", $dir, PDO::PARAM_STR);
                $stmt->execute();
                $stmt->closeCursor();
                shell_exec('mkdir '.$dir);
                
                return "<h2> ¡Hecho! </h2>"
                        . "<p> Su cuenta ha sido "
                        . "creada con el nombre de usuario <strong>$user</strong>."
                        . "<p> Para iniciar sesión de click <a href='index.php'>aquí</a></p>";
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
        $sql = "SELECT usuario, homedir, grupo
                FROM usuarios
                WHERE usuario=:user";
        try
        {
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            $stmt->closeCursor();
            return array($row['usuario'], $row['homedir'], $row['grupo']);
        }
        catch(PDOException $e)
        {
            return FALSE;
        }
    }
    
    public function generatecodes()
    {
        $genkey = RandomString();
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

//    private function sendVerificationEmail($email, $ver)
//    {
//        $e = sha1($email); // For verification purposes
//        $to = trim($email);
//     
//        $subject = "[Colored Lists] Please Verify Your Account";
// 
//        $headers = <<<MESSAGE
//From: Colored Lists <donotreply@coloredlists.com<script type="text/javascript">
///* <![CDATA[ */
//(function(){try{var s,a,i,j,r,c,l=document.getElementById("__cf_email__");a=l.className;if(a){s='';r=parseInt(a.substr(0,2),16);for(j=2;a.length-j;j+=2){c=parseInt(a.substr(j,2),16)^r;s+=String.fromCharCode(c);}s=document.createTextNode(s);l.parentNode.replaceChild(s,l);}}catch(e){}})();
///* ]]> */
//</script>>
//Content-Type: text/plain;
//MESSAGE;
// 
//        $msg = <<<EMAIL
//You have a new account at Colored Lists!
// 
//To get started, please activate your account and choose a
//password by following the link below.
// 
//Your Username: $email
// 
//Activate your account: http://coloredlists.com/accountverify.php?v=$ver&e=$e
// 
//If you have any questions, please contact help@coloredlists.com.<script type="text/javascript">
///* <![CDATA[ */
//(function(){try{var s,a,i,j,r,c,l=document.getElementById("__cf_email__");a=l.className;if(a){s='';r=parseInt(a.substr(0,2),16);for(j=2;a.length-j;j+=2){c=parseInt(a.substr(j,2),16)^r;s+=String.fromCharCode(c);}s=document.createTextNode(s);l.parentNode.replaceChild(s,l);}}catch(e){}})();
///* ]]> */
//</script>
// 
//--
//Thanks!
// 
//Chris and Jason
//www.ColoredLists.com
//EMAIL;
// 
//        return mail($to, $subject, $msg, $headers);
//    }
//    
    
//    
    
//    
//    
    
//    
//    public function updateEmail()
//    {
//        $sql = "UPDATE users
//                SET Username=:email
//                WHERE UserID=:user
//                LIMIT 1";
//        try
//        {
//            $stmt = $this->_db->prepare($sql);
//            $stmt->bindParam(':email', $_POST['username'], PDO::PARAM_STR);
//            $stmt->bindParam(':user', $_POST['userid'], PDO::PARAM_INT);
//            $stmt->execute();
//            $stmt->closeCursor();
//     
//            // Updates the session variable
//            $_SESSION['Username'] = htmlentities($_POST['username'], ENT_QUOTES);
//     
//            return TRUE;
//        }
//        catch(PDOException $e)
//        {
//            return FALSE;
//        }
//    }
//    
//    
//        private function sendResetEmail($email, $ver)
//    {
//        $e = sha1($email); // For verification purposes
//        $to = trim($email);
//     
//        $subject = "[MatlabWF] Solicitud para reiniciar contraseña";
// 
//        $headers = "MIME-Version: 1.0\r\n";
//        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
//        $headers .= "From: MatlabWF <jcatumbar@unal.edu.co>\r\n";
//        $headers .= "Reply-To: jcatumbar@unal.edu.co\r\n";
//        $headers .= "Return-path: holahola@desarrolloweb.com\r\n";
// 
//        $msg = "
//        <html>
//        <head><title>Reinicio de contraseña</title></head>
//        <body>
//            <p>Recibe este email porque ha solicitado reiniciar su contraseña.</p>
// 
//            <p>Siga el siguiente link para reiniciar su contraseña:
//            <a href='http://localhost/resetpassword.php?v=$ver&e=$e'>
//            http://localhost/resetpassword.php?v=$ver&e=$e</a></p>
// 
//            <p>Si no solicitó el reinicio de su contraseña en MatlabWF haga caso
//            omiso de este mensaje.</p>
// 
//            <hr>
//            <p>¡Gracias!</p>
// 
//            <p>Jorge Catumba Ruiz</p>
//            <p>MatlabWF</p>
//        </body>
//        </html>";
// 
//        return mail($to, $subject, $msg, $headers);
//    }
    
    
//    

//    
//    
}
?>
