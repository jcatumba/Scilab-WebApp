<!DOCTYPE html>
<html>
<head>
    <!--Title-->
    <title>MatlabWF | <?php echo $pageTitle?></title>

    <!--Meta-->
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta name="author" content="Jorge Catumba Ruiz">
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![end if]-->

    <!--CSS-->
    <link rel="stylesheet" href="css/estilo.css" type="text/css" />
    <link rel="stylesheet" href="css/agrid.css" type="text/css" />
    <link rel="shortcut icon" type="image/x-icon" href="/imagenes/favicon.ico" />
    <!--<link href='http://fonts.googleapis.com/css?family=Trocchi' rel='stylesheet' type='text/css'>-->

    <!--JavaScript-->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script type="text/javascript" src="js/jquery.js"><\/script>')</script>
    <script>
    $(document).ready(function()
    {
        var t=setTimeout("fade()",3000);
        // The DOM (document object model) is constructed
        // We will initialize and run our plugin here
    });
    function fade(){
        $(".message").fadeOut(300);
    }
    </script>
</head>

<body>
    <div id="page-wrap">
        <div id="header">

            <h1><a href="/">Matlab WebFramework</a></h1>

            <div id="control">

                <?php
                    if(isset($_SESSION['LoggedIn']) && isset($_SESSION['Username'])
                    && $_SESSION['LoggedIn']==1):
                    include_once 'inc/class.users.inc.php';
                    $users = new ColoredListsUsers($db);
                    list($usuario, $homedir, $grupo) = $users->retrieveAccountInfo();
                ?>
                    <p><a href="/account.php"><?php echo $usuario ?></a> | <a href="/logout.php">salir</a></p>
                <?php else: ?>
                    <!--<p><a class="button" href="/signup.php">Sign up</a> &nbsp; <a class="button" href="/login.php">Log in</a></p>-->
                <?php endif; ?>

            </div>

        </div>
