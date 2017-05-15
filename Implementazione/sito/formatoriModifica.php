<?php
session_start();
if(isset($_SESSION["email"]) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
  include_once "connection.php";
  include_once "view/formatoriModifica.php";
  include_once "model/formatori.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
