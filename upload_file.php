<?php
include_once "common/base.php";
    $pageTitle = "Subiendo archivos";
    include_once "common/header.php";
    
if (!empty($_FILES["file"]) && ($_FILES["file"]["type"] == "text/plain")) {
  echo $_FILES["file"]["name"];
  if ($_FILES["file"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    header("Location: visordearchivos.php?event=fail");
  } else {
    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

    if (file_exists($_POST['folder'] . "/" . $_FILES["file"]["name"])) {
          echo $_FILES["file"]["name"] . " already exists. ";
          header("Location: visordearchivos.php?event=exists");
    } else {
      move_uploaded_file($_FILES["file"]["tmp_name"], $_POST['folder'] . "/" . $_FILES["file"]["name"]);
          echo "Stored in: " . $_POST['folder'] . "/" . $_FILES["file"]["name"];
          header("Location: visordearchivos.php?event=done");
      }
    }
  }
else
  {
  echo "Invalid file";
  header("Location: visordearchivos.php?event=fail");
  }
  
  include_once "common/footer.php";
?>
