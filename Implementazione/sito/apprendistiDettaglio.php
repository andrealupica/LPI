<?php
session_start();
if(isset($_SESSION["email"])){
  include_once "connection.php";
  include_once "view/apprendistiDettaglio.php";
  include_once "model/apprendisti.php";
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
