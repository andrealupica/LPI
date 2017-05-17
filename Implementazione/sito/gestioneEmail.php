<?php
session_start();
if(isset($_SESSION["email"]) && ($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master")){
  include_once "connection.php";
  include_once "view/gestioneEmail.php";
  include_once "model/gestioneEmail.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
