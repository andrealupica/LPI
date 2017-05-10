<?php
session_start();
if(isset($_SESSION["email"])){
  include_once "connection.php";
  include_once "view/apprendistiModifica.php";
  include_once "model/apprendistiModifica.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
