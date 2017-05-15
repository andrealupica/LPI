<?php
session_start();
  if(isset($_SESSION["email"])){
  include_once "connection.php";
  include_once "view/cambioPassword.php";
  include_once "model/cambioPassword.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
