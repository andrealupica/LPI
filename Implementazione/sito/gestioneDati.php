<?php
session_start();
if(isset($_SESSION["email"]) && $_SESSION["tipo"]=="master"){
  include_once "connection.php";
  include_once "view/gestioneDati.php";
  include_once "model/gestioneDati.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
