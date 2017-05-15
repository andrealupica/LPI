<?php
session_start();
if(isset($_SESSION["email"])){
  include_once "connection.php";
  include_once "view/datori.php";
  include_once "model/datori.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
