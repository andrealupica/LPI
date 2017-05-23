<?php
include_once "../connection.php";
// quando viene selezionato un gruppo di email
if(isset($_POST["gruppoModificato"])){
  $gruppoId = $_POST["gruppoModificato"]; // non lo uso
  try{
    // seleziona il nome e l'email del formatore
    $query = $conn->prepare("SELECT for_email AS 'email',for_nome AS 'nome' from formatore where for_flag=1 group by for_nome");
    $query->execute();
    $nomi=array();
    while($row=$query->fetch(PDO::FETCH_ASSOC)){
      // seleziono email, nome e controllo se esiste come relazione del gruppo.
      $query1 = $conn->prepare("SELECT for_email AS 'email',for_nome AS 'nome',
              (SELECT count(for_email) from gruefor where grue_id=:id AND for_email=:email) AS 'presente'
              from formatore where for_flag=1 AND for_email=:email1 group by for_nome");
      $query1->bindParam(':id',$gruppoId);
      $query1->bindParam(':email',$row["email"]);
            $query1->bindParam(':email1',$row["email"]);
      $query1->execute();
      $row1=$query1->fetch(PDO::FETCH_ASSOC);
      // manda i dati in un array
      $nomi[]=$row1['nome']."/".$row1['email']."/".$row1["presente"];
    }
    echo json_encode($nomi);
  }
  catch(PDOException $e){
    echo $e;
  }

}

?>
