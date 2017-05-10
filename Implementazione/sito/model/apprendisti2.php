<?php
include_once "../connection.php";
if(isset($_POST["datoreSel"])){
  $datoreId = $_POST["datoreSel"];
  $query = $conn->prepare("SELECT for_nome AS 'nome',for_email AS 'email' from formatore where dat_id=:datore");
  $query->bindParam(':datore',$datoreId);
  $query->execute();
  $nomi=array();
  while($row=$query->fetch(PDO::FETCH_ASSOC)){
    $nomi[]=$row['nome']."/".$row['email'];
  }
  echo json_encode($nomi);
}
?>
