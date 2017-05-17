<?php

// quando cerco di inserire un formatore
if(isset($_POST["insert"])  AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){ // dato che il submit viene effettuato con il controllo non serve il controllo del set di tutto
  $nome = $_POST["insertNome"];
  $indirizzo = $_POST["insertIndirizzo"];
  $domicilio = $_POST["insertDomicilio"];
  $telefono = $_POST["insertTelefono"];
  $emailHR = $_POST["insertEmailHR"];
  $nomeHR = $_POST["insertNomeHR"];
  $telefonoHR = $_POST["insertTelefonoHR"];
  try{
    // seleziono i formatori con quell email
    $dat = $conn->prepare("SELECT dat_id,dat_flag AS 'flag' from datore where dat_nome=:nome");
    $dat->bindParam(':nome',$nome);
    $dat->execute();
    $row = $dat->fetch(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e)
  {
    //echo $e;
  }
    // controllo che esiste nel db
  if($dat->rowCount()==1){
    if($row["flag"]==0){ // se nascosto, mostralo visibile
      try{
        $query=$conn->prepare("UPDATE datore SET dat_indirizzo =:indirizzo,dat_domicilio =:domicilio,dat_telefono =:telefono,
                                dat_flag=1,dat_emailHR=:emailHR,dat_nomeHR =:nomeHR, dat_telefonoHR=:telefonoHR
                                 where dat_nome=:nome");
        $query->bindParam(':nome',$nome);
        $query->bindParam(':indirizzo',$indirizzo);
        $query->bindParam(':domicilio',$domicilio);
        $query->bindParam(':telefono',$telefono);
        $query->bindParam(':emailHR',$emailHR);
        $query->bindParam(':nomeHR',$nomeHR);
        $query->bindParam(':telefonoHR',$telefonoHR);
        $query->execute();
        echo "<script> location.href='datori.php'</script>";
      }
      catch(PDOException $e)
      {
        //echo $e;
      }
    }
    else{
      echo  "<script>document.getElementById('errori').innerHTML='il datore esiste già';document.getElementById('errori').setAttribute('class','col-xs-6 alert alert-danger')</script>";
    }
  }
  // se non esiste lo aggiungo
  else{
    try{
      $query = $conn->prepare("INSERT INTO datore(dat_nome, dat_indirizzo,dat_domicilio,dat_telefono,dat_emailHR,dat_nomeHR,dat_telefonoHR)
                              values(:nome,:indirizzo,:domicilio,:telefono,:emailHR,:nomeHR,:telefonoHR)");
      $query->bindParam(':nome',$nome);
      $query->bindParam(':indirizzo',$indirizzo);
      $query->bindParam(':domicilio',$domicilio);
      $query->bindParam(':telefono',$telefono);
      $query->bindParam(':emailHR',$emailHR);
      $query->bindParam(':nomeHR',$nomeHR);
      $query->bindParam(':telefonoHR',$telefonoHR);
      // se non ci sono stati errori ricarico la pagina
      if($query->execute()!=false){
        echo "<script> location.href='datori.php'</script>";
      }
    }
    catch(PDOException $e)
    {
      //echo $e;
    }
  }
}

// se voglio eliminare un formatore
if(isset($_POST["datoreCancellato"]) AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
  $id = $_POST["datoreCancellato"];
  try{
    $query= $conn->prepare("UPDATE datore set dat_flag=0  where dat_id=:id");
    $query->bindParam(':id',$id);
    $query->execute();
    echo "<script> location.href='datori.php'</script>";
  }
  catch(PDOException $e)
  {
    //echo $e;
  }
}

// se voglio modificare un formatoe
if(isset($_POST["modifica"]) && isset($_POST["dati"]) AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
  $id = $_POST["modifica"];
  echo $id;
  $nome = $_POST["insertNome"];
  $indirizzo = $_POST["insertIndirizzo"];
  $domicilio = $_POST["insertDomicilio"];
  $telefono = $_POST["insertTelefono"];
  $emailHR = $_POST["insertEmailHR"];
  $nomeHR = $_POST["insertNomeHR"];
  $telefonoHR = $_POST["insertTelefonoHR"];
  try{
    // seleziono i formatori con quell email
    $dat = $conn->prepare("SELECT dat_id from datore where dat_nome=:nome AND dat_id !=:id");
    $dat->bindParam(':nome',$nome);
    $dat->bindParam(':id',$id);
    $dat->execute();
  }
  catch(PDOException $e)
  {
  echo $e;
  }
  // controllo che non esista già quel nome
  if($dat->rowCount()==0){
    try{
      $query=$conn->prepare("UPDATE datore SET dat_nome=:nome, dat_indirizzo =:indirizzo,dat_domicilio =:domicilio,dat_telefono =:telefono,
                              dat_emailHR=:emailHR,dat_nomeHR =:nomeHR, dat_telefonoHR=:telefonoHR where dat_id=:id");
      $query->bindParam(':nome',$nome);
      $query->bindParam(':indirizzo',$indirizzo);
      $query->bindParam(':domicilio',$domicilio);
      $query->bindParam(':telefono',$telefono);
      $query->bindParam(':emailHR',$emailHR);
      $query->bindParam(':nomeHR',$nomeHR);
      $query->bindParam(':telefonoHR',$telefonoHR);
      $query->bindParam('id',$id);
      $query->execute();
      echo "<script> location.href='datori.php'</script>";
    }
    catch(PDOException $e)
    {
      //echo $e;
    }
  }
  // avviso della ripetizione
  else{
    //echo "<script>alert('esiste già')</script>";
    echo  "<script>document.getElementById('errori').innerHTML='esiste già un datore con quel nome';document.getElementById('errori').setAttribute('class','col-xs-6 alert alert-danger')</script>";
  }
}

?>
