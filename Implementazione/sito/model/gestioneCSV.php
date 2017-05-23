<?php

    ###################################################################### download del file
  if(isset($_POST["download"]) AND isset($_SESSION["email"]) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
    $file = "uploads/".$_POST["download"];

    echo $file;
    // se il file esiste, fai ciò che devi fare e poi scarica con readfile
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
    else{
      echo  "<script>document.getElementById('errori').innerHTML='il file non esiste';document.getElementById('errori').setAttribute('class','col-xs-6 alert alert-danger')</script>";
    }
  }

?>
