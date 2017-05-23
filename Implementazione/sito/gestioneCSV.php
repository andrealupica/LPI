<?php
session_start();
if(isset($_SESSION["email"]) && ($_SESSION["tipo"]=="master" OR $_SESSION["tipo"]=="admin")){
  include_once "connection.php";
  include_once "view/gestioneCSV.php";
  include_once "model/gestioneCSV.php";
  include_once "model/importazioneDati.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
