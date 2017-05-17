<?php
  if(isset($_POST["nomeGruppo"]) AND !empty($_POST["nomeGruppo"]) AND isset($_POST['createGruppo']) AND isset($_SESSION["email"]) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
    $nomeGruppo = $_POST["nomeGruppo"];
    $createGruppo = $_POST["createGruppo"];
    $dum = implode(",",$createGruppo);
    $destinatari = explode(",",$dum);
    echo "<script>console.log('".count($destinatari)."')</script>";
    for ($i=0; $i < count($destinatari) ; $i++) {
      echo "<script>console.log('".$i.":".$destinatari[$i]."')</script>";
    }

    ###################################################################### creazione del nome del gruppo
    try{
      $esistente = $conn->prepare("SELECT grue_id AS 'id',grue_flag AS 'flag' from gruppoEmail where grue_nome=:nome");
      $esistente->bindParam(':nome',$nomeGruppo);
      $esistente->execute();
    }
    catch(PDOException $e){
    //  echo $e;
    }
    if($esistente->rowCount()==1){
      $r = $esistente->fetch(PDO::FETCH_ASSOC);
      if($r["flag"]==0){ // rimostralo
        try{
          $gruppo = $conn->prepare("UPDATE gruppoEmail set grue_flag=1 where grue_id=:id");
          $gruppo->bindParam(':id',$r["id"]);
          $gruppo->execute();
        }
        catch(PDOException $e)
        {
          echo $e;
        }
        ################################################################# collegamento gruppo email
        // svuoto i collegamenti
        $svuota = $conn->prepare("DELETE from gruefor where grue_id=:id");
        $svuota->bindParam(':id',$r["id"]);
        $svuota->execute();
        for ($i=0; $i < count($destinatari) ; $i++) {
          echo "<script>console.log('".$i.":".$destinatari[$i]."')</script>";
          try{
            $query = $conn->prepare("INSERT INTO gruefor(grue_id,for_email) value(:id,:email)");
            $query->bindParam(':id',$r["id"]);
            $query->bindParam(':email',$destinatari[$i]);
            $query->execute();
          }
          catch(PDOException $e){
            echo $e;
          }
        }
      }
      else{
        echo  "<script>document.getElementById('errori').innerHTML='il nome per il gruppo è già esistente';document.getElementById('errori').setAttribute('class','col-xs-6 alert alert-danger')</script>";
      }
    }
    else{ // crealo
      try{
        $query = $conn->prepare("INSERT into gruppoEmail(grue_nome) values(:nome)");
        $query->bindParam(':nome',$nomeGruppo);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }

        ################################################################# collegamento gruppo email
        // prendo l'id del gruppo
      try{
        $idGruppo = $conn->prepare("SELECT grue_id AS 'id'from gruppoEmail where grue_nome=:nome");
        $idGruppo->bindParam(':nome',$nomeGruppo);
        $idGruppo->execute();
        $r = $idGruppo->fetch(PDO::FETCH_ASSOC);
        $id = $r["id"];
      }
      catch(PDOException $e){
        echo $e;
      }
      for ($i=0; $i < count($destinatari) ; $i++) {
        try{
          $query = $conn->prepare("INSERT INTO gruefor(grue_id,for_email) value(:id,:email)");
          $query->bindParam(':id',$id);
          $query->bindParam(':email',$destinatari[$i]);
          $query->execute();
        }
        catch(PDOException $e){
          echo $e;
        }
      }
    }
    echo "<script> location.href='gestioneEmail.php'</script>";
  }

  ################################################################### eliminazioe del gruppo
  if(isset($_POST["eliminaGruppo"]) AND !empty($_POST["eliminaGruppo"])  AND isset($_SESSION["email"]) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
    $id=$_POST["eliminaGruppo"];
    //echo "<script>alert('".$id."')</script>";
    try{
      $query = $conn->prepare("UPDATE gruppoEmail set grue_flag=0 where grue_id=:id");
      $query->bindParam(':id',$id);
      $query->execute();
    }
    catch(PDOException $e){
      echo $e;
    }
    echo "<script> location.href='gestioneEmail.php'</script>";
  }



  ################################################################# modifica del gruppo

  if(isset($_POST["gruppoModificato"]) AND empty($_POST["eliminaGruppo"]) AND isset($_POST["modificaGruppo"]) AND isset($_SESSION["email"]) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
    $id = $_POST['gruppoModificato'];
    // svuoto i collegamenti
    $svuota = $conn->prepare("DELETE from gruefor where grue_id=:id");
    $svuota->bindParam(':id',$id);
    $svuota->execute();
    $destinatari = $_POST["modificaGruppo"];
    for ($i=0; $i < count($destinatari) ; $i++) {
      echo "<script>console.log('".$i.":".$destinatari[$i]."')</script>";
      try{
        $query = $conn->prepare("INSERT INTO gruefor(grue_id,for_email) value(:id,:email)");
        $query->bindParam(':id',$id);
        $query->bindParam(':email',$destinatari[$i]);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }
    }
    echo "<script> location.href='gestioneEmail.php'</script>";
  }

?>
