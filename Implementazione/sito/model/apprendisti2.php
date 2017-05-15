<?php
include_once "../connection.php";
// quando viene selezionato un datore
if(isset($_POST["datoreSel"])){
  $datoreId = $_POST["datoreSel"];
  // seleziona il nome e l'email del formatore
  $query = $conn->prepare("SELECT for_nome AS 'nome',for_email AS 'email' from formatore where dat_id=:datore AND for_flag=1");
  $query->bindParam(':datore',$datoreId);
  $query->execute();
  $nomi=array();
  while($row=$query->fetch(PDO::FETCH_ASSOC)){
    // manda i dati in un array
    $nomi[]=$row['nome']."/".$row['email'];
  }
  echo json_encode($nomi);
}
?>
