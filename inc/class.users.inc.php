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
            $dsn = new PDO("pgsql:host=".DB_HOST.";dbname=".DB_NAME.";user=".DB_USER.";password=".DB_PASS);
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
        $email = trim($_POST['email']);
        $key = trim($_POST['accesskey']);
        $user = trim($_POST['user']);
        $pass = trim($_POST['password']);
        $dir = trim("usersfiles/".$user);

        $sql = "SELECT COUNT(emails) AS theCount FROM usuarios WHERE email=:email";
        if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            if($row['theCount']!=0) {
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
         
        $sql = "INSERT INTO usuarios (usuario,email,keyaccess,password,homedir) VALUES(:user,:email,:ver,MD5(:pass),:homedir)";
        if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":ver", $key, PDO::PARAM_INT);
            $stmt->bindParam(":user", $user, PDO::PARAM_STR);
            $stmt->bindParam(":pass", $pass, PDO::PARAM_STR);
            $stmt->bindParam(":homedir", $dir, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
            
            return "<h2> ¡Hecho! </h2>"
                    . "<p> Su cuenta ha sido "
                    . "creada con el nombre de usuario <strong>$user</strong>.";
            /*
             * If the UserID was successfully
             * retrieved, create a default directory.
             */
            /*$sqlm = "INSERT INTO files (usuario,homedir) VALUES ($user,$dir)";
            if($Stmt = $this->_db->prepare($sqlm)) {
                $stmt->bindParam(":user", $user, PDO::PARAM_STR);
                $stmt->bindParam(":dir", $dir, PDO::PARAM_STR);
                $Stmt->execute();
                $Stmt->closeCursor();
                
            } else {
                return "<h2> Error </h2>"
                    . "<p> Su cuenta fue creada, pero "
                    . "la creación de su directorio falló. </p>";
            }*/
        } else {
            return "<h2> Error </h2><p> No se ha podido insertar la "
                . "información de usuario en la base de datos. </p>";
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
//    public function verifyAccount()
//    {
//        $sql = "SELECT Username
//                FROM users
//                WHERE ver_code=:ver
//                AND SHA1(Username)=:user
//                AND verified=0";
// 
//        if($stmt = $this->_db->prepare($sql))
//        {
//            $stmt->bindParam(':ver', $_GET['v'], PDO::PARAM_STR);
//            $stmt->bindParam(':user', $_GET['e'], PDO::PARAM_STR);
//            $stmt->execute();
//            $row = $stmt->fetch();
//            if(isset($row['Username']))
//            {
//                // Logs the user in if verification is successful
//                $_SESSION['Username'] = $row['Username'];
//                $_SESSION['LoggedIn'] = 1;
//            }
//            else
//            {
//                return array(4, "<h2>Verification Error</h2>n"
//                    . "<p>This account has already been verified. "
//                    . "Did you <a href="/password.php">forget "
//                    . "your password?</a>");
//            }
//            $stmt->closeCursor();
// 
//            // No error message is required if verification is successful
//            return array(0, NULL);
//        }
//        else
//        {
//            return array(2, "<h2>Error</h2>n<p>Database error.</p>");
//        }
//    }
//    
//    public function updatePassword()
//    {
//        if(isset($_POST['p'])
//        && isset($_POST['r'])
//        && $_POST['p']==$_POST['r'])
//        {
//            $sql = "UPDATE users
//                    SET Password=MD5(:pass), verified=1
//                    WHERE ver_code=:ver
//                    LIMIT 1";
//            try
//            {
//                $stmt = $this->_db->prepare($sql);
//                $stmt->bindParam(":pass", $_POST['p'], PDO::PARAM_STR);
//                $stmt->bindParam(":ver", $_POST['v'], PDO::PARAM_STR);
//                $stmt->execute();
//                $stmt->closeCursor();
// 
//                return TRUE;
//            }
//            catch(PDOException $e)
//            {
//                return FALSE;
//            }
//        }
//        else
//        {
//            return FALSE;
//        }
//    }
//    
//    
//    public function retrieveAccountInfo()
//    {
//        $sql = "SELECT UserID, ver_code
//                FROM users
//                WHERE Username=:user";
//        try
//        {
//            $stmt = $this->_db->prepare($sql);
//            $stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
//            $stmt->execute();
//            $row = $stmt->fetch();
//            $stmt->closeCursor();
//            return array($row['UserID'], $row['ver_code']);
//        }
//        catch(PDOException $e)
//        {
//            return FALSE;
//        }
//    }
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
//    public function updatePassword()
//    {
//        if(isset($_POST['p'])
//        && isset($_POST['r'])
//        && $_POST['p']==$_POST['r'])
//        {
//            $sql = "UPDATE users
//                    SET Password=MD5(:pass), verified=1
//                    WHERE ver_code=:ver
//                    LIMIT 1";
//            try
//            {
//                $stmt = $this->_db->prepare($sql);
//                $stmt->bindParam(":pass", $_POST['p'], PDO::PARAM_STR);
//                $stmt->bindParam(":ver", $_POST['v'], PDO::PARAM_STR);
//                $stmt->execute();
//                $stmt->closeCursor();
// 
//                return TRUE;
//            }
//            catch(PDOException $e)
//            {
//                return FALSE;
//            }
//        }
//        else
//        {
//            return FALSE;
//        }
//    }
//    
//    public function deleteAccount()
//    {
//        if(isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn']==1)
//        {
//            // Delete list items
//            $sql = "DELETE FROM list_items
//                    WHERE ListID=(
//                        SELECT ListID
//                        FROM lists
//                        WHERE UserID=:user
//                        LIMIT 1
//                    )";
//            try
//            {
//                $stmt = $this->_db->prepare($sql);
//                $stmt->bindParam(":user", $_POST['user-id'], PDO::PARAM_INT);
//                $stmt->execute();
//                $stmt->closeCursor();
//            }
//            catch(PDOException $e)
//            {
//                die($e->getMessage());
//            }
// 
//            // Delete the user's list(s)
//            $sql = "DELETE FROM lists
//                    WHERE UserID=:user";
//            try
//            {
//                $stmt = $this->_db->prepare($sql);
//                $stmt->bindParam(":user", $_POST['user-id'], PDO::PARAM_INT);
//                $stmt->execute();
//                $stmt->closeCursor();
//            }
//            catch(PDOException $e)
//            {
//                die($e->getMessage());
//            }
//             
//            // Delete the user
//            $sql = "DELETE FROM users
//                    WHERE UserID=:user
//                    AND Username=:email";
//            try
//            {
//                $stmt = $this->_db->prepare($sql);
//                $stmt->bindParam(":user", $_POST['user-id'], PDO::PARAM_INT);
//                $stmt->bindParam(":email", $_SESSION['Username'], PDO::PARAM_STR);
//                $stmt->execute();
//                $stmt->closeCursor();
//            }
//            catch(PDOException $e)
//            {
//                die($e->getMessage());
//            }
// 
//            // Destroy the user's session and send to a confirmation page
//            unset($_SESSION['LoggedIn'], $_SESSION['Username']);
//            header("Location: /gone.php");
//            exit;
//        }
//        else
//        {
//            header("Location: /account.php?delete=failed");
//            exit;
//        }
//    }
//    
//    public function resetPassword()
//    {
//        $sql = "UPDATE users
//                SET verified=0
//                WHERE Username=:user
//                LIMIT 1";
//        try
//        {
//            $stmt = $this->_db->prepare($sql);
//            $stmt->bindParam(":user", $_POST['username'], PDO::PARAM_STR);
//            $stmt->execute();
//            $stmt->closeCursor();
//        }
//        catch(PDOException $e)
//        {
//            return $e->getMessage();
//        }
// 
//        // Send the reset email
//        if(!$this->sendResetEmail($_POST['username'], $v))
//        {
//            return "Sending the email failed!";
//        }
//        return TRUE;
//    }
//    
//    private function sendResetEmail($email, $ver)
//    {
//        $e = sha1($email); // For verification purposes
//        $to = trim($email);
//     
//        $subject = "[Colored Lists] Request to Reset Your Password";
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
//We just heard you forgot your password! Bummer! To get going again,
//head over to the link below and choose a new password.
// 
//Follow this link to reset your password:
//http://coloredlists.com/resetpassword.php?v=$ver&e=$e
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
}
?>
