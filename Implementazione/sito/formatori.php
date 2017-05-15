<?php
session_start();
if(isset($_SESSION["email"])){
  include_once "connection.php";
  include_once "view/formatori.php";
  include_once "model/formatori.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
