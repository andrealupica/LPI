<?php
session_start();
if(isset($_SESSION["email"])AND ($_SESSION['tipo']=="master" OR $_SESSION['tipo']=="admin")){
  include_once "connection.php";
  include_once "view/apprendistiModifica.php";
  include_once "model/apprendisti.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
