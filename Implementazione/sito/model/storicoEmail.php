<?php
// quando cerco di eliminare un apprendista
if(isset($_POST["emailCancellata"]) AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
  $elimina = $_POST["emailCancellata"];
  //echo "id: ".$elimina;
  //echo "post: ".$elimina;
  try{
    $query= $conn->prepare("UPDATE email set ema_flag=0 where ema_id=:id");
    $query->bindParam(':id',$elimina);
    $query->execute();
    echo "<script> location.href='gestioneEmail.php'</script>";
  }
  catch(PDOException $e)
  {
    //echo $e;
  }
}
?>
