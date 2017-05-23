<?php

    ###################################################################### creazione del nome del gruppo
  if(isset($_POST["nomeGruppo"]) AND !empty($_POST["nomeGruppo"]) AND isset($_POST['createGruppo']) AND isset($_SESSION["email"]) AND ($_SESSION['tipo']=="master" OR $_SESSION['tipo']=="admin")){
    $nomeGruppo = $_POST["nomeGruppo"];
    $createGruppo = $_POST["createGruppo"];
    $dum = implode(",",$createGruppo); // unisco tutti i destinatari in una stringa
    $destinatari = explode(",",$dum); // creo l'array di destinatari

    try{
      // controllo che quel gruppo esista già
      $esistente = $conn->prepare("SELECT grue_id AS 'id',grue_flag AS 'flag'
        from gruppoEmail where grue_nome=:nome");
      $esistente->bindParam(':nome',$nomeGruppo);
      $esistente->execute();
    }
    catch(PDOException $e){
    }
    // se esiste controllo se è stato eliminato
    if($esistente->rowCount()==1){
      $r = $esistente->fetch(PDO::FETCH_ASSOC);
      if($r["flag"]==0){ // rimostralo
        try{
          // se esiste già prima lo mostro
          $gruppo = $conn->prepare("UPDATE gruppoEmail set grue_flag=1 where grue_id=:id");
          $gruppo->bindParam(':id',$r["id"]);
          $gruppo->execute();
        }
        catch(PDOException $e)
        {
          echo $e;
        }
        ################################################################# collegamento gruppo email
        // poi svuoto i collegamenti
        $svuota = $conn->prepare("DELETE from gruefor where grue_id=:id");
        $svuota->bindParam(':id',$r["id"]);
        $svuota->execute();
        // e infine rinserisco i collegamenti
        for ($i=0; $i < count($destinatari) ; $i++) {
          //echo "<script>console.log('".$i.":".$destinatari[$i]."')</script>";
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
        echo "<script> location.href='gestioneEmail.php'</script>";
      }
      // se il gruppo esiste già e non è stato eliminato
      else{
        echo  "<script>document.getElementById('errori').innerHTML='il nome per il gruppo è già esistente';document.getElementById('errori').setAttribute('class','col-xs-6 alert alert-danger')</script>";
      }
    }
    else{ // crealo
      try{
        // prima creo il gruppo
        $query = $conn->prepare("INSERT into gruppoEmail(grue_nome) values(:nome)");
        $query->bindParam(':nome',$nomeGruppo);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }

        // prendo l'id del gruppo
      try{
        // poi seleziono l'id da usare per la foreign key
        $idGruppo = $conn->prepare("SELECT grue_id AS 'id'from gruppoEmail where grue_nome=:nome");
        $idGruppo->bindParam(':nome',$nomeGruppo);
        $idGruppo->execute();
        $r = $idGruppo->fetch(PDO::FETCH_ASSOC);
        $id = $r["id"];
      }
      catch(PDOException $e){
        echo $e;
      }
      // faccio il collegamento gruppo-email formatore
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
        echo "<script> location.href='gestioneEmail.php'</script>";
    }
  }

  ################################################################### eliminazioe del gruppo
  if(isset($_POST["eliminaGruppo"]) AND !empty($_POST["eliminaGruppo"])  AND isset($_SESSION["email"]) AND ($_SESSION['tipo']=="master" OR $_SESSION['tipo']=="admin")){
    $id=$_POST["eliminaGruppo"];
    //echo "<script>alert('".$id."')</script>";
    try{
      // setto il flag a 0 così viene nascosto dal sito.
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

  if(isset($_POST["gruppoModificato"]) AND empty($_POST["eliminaGruppo"]) AND isset($_POST["modificaGruppo"]) AND isset($_SESSION["email"]) AND ($_SESSION['tipo']=="master" OR $_SESSION['tipo']=="admin")){
    $id = $_POST['gruppoModificato'];
    // svuoto i collegamenti
    $svuota = $conn->prepare("DELETE from gruefor where grue_id=:id");
    $svuota->bindParam(':id',$id);
    $svuota->execute();
    $destinatari = $_POST["modificaGruppo"];
    // reinserisco i nuovi dati
    for ($i=0; $i < count($destinatari) ; $i++) {
      //echo "<script>console.log('".$i.":".$destinatari[$i]."')</script>";
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

    ################################################################# invio email
    if(isset($_POST["inviaInput"]) AND isset($_SESSION["email"]) AND ($_SESSION['tipo']=="master" OR $_SESSION['tipo']=="admin")){
      $gruppo = $_POST["selectGruppo"];
      $destinatario = $_POST["selectDestinatario"];
      $oggetto = $_POST["oggetto"];
      $messaggio = $_POST["messaggio"];
      $mittente = $_SESSION["email"];
      $dest1 = array();
      //echo "in";
      //echo count($gruppo);
      for ($i=0; $i < count($gruppo); $i++) {
        try{
          // seleziono le email presenti nel gruppo e le inserisco nell'array
          $query = $conn->prepare("SELECT gruefor.for_email AS 'email' from gruefor
            JOIN formatore form ON form.for_email=gruefor.for_email
            where grue_id =:id and form.for_flag=1");
          $query->bindParam(':id',$gruppo[$i]);
          $query->execute();
          while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $dest1[] = $row['email'];
          }
        }
        catch(PDOException $e){
          echo $e;
        }
      }
      //echo count($gruppo);
      // aggiungo i destinatari singoli
      for ($i=0; $i <count($destinatario); $i++) {
        $dest1[] = $destinatario[$i];
      }

      // rimuovo le duplicazioni
      $dest = array_unique($dest1);

      // prendo la data di invio
      $ora = date('Y-m-d h:i:s');

      try{
        // inserisco le informazioni delle email nel db
        $query = $conn->prepare("INSERT INTO email(ema_messaggio,ema_data, ema_oggetto, ute_email)
                                            VALUES (:messaggio,:data,:oggetto,:mittente)");
        $query->bindParam(':messaggio',$messaggio);
        $query->bindParam(':data',$ora);
        $query->bindParam(':oggetto',$oggetto);
        $query->bindParam(':mittente',$mittente);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }

      try{
        // seleziono l'id tramite la data che sarà sicuramente univoca anch essa
        $query = $conn->prepare("SELECT ema_id from email where ema_data=:data");
        $query->bindParam(':data',$ora);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $idEmail = $row['ema_id'];
      }
      catch(PDOException $e){
        echo $e;
      }

      for ($i=0; $i <count($dest) ; $i++) {
        try{
          // inserisco nella tabella il collegamento email-email formatore
          $insert = $conn->prepare("INSERT into forema(for_email,ema_id) values(:email,:id)");
          $insert->bindParam(':email',$dest[$i]);
          $insert->bindParam(':id',$idEmail);
          $insert->execute();
          $headers = "MIME-Version: 1.0" . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers .= 'From: <'.$mittente.'>'. "\r\n";
          mail($dest[$i],$oggetto,$messaggio,$headers);   // invio l'email
          echo "<script> location.href='gestioneEmail.php'</script>";
        }
        catch(PDOException $e){
          echo $e;
        }
      }
    }


?>
