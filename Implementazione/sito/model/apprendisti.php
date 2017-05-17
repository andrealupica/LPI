<?php

// quando cerco di inserire un apprendista
if(isset($_POST["insert"])  AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){ // dato che il submit viene effettuato con il controllo non serve il controllo del set di tutto
  $nome = $_POST["insertNome"];
  $nascita = $_POST["insertNascita"];
  $contratto = $_POST["insertContratto"];
  $statuto = $_POST["insertStatuto"];
  $indirizzo = $_POST["insertIndirizzo"];
  $domicilio = $_POST["insertDomicilio"];
  $telefono = $_POST["insertTelefono"];
  $professione = $_POST["insertProfessione"];
  $sede = $_POST["insertSede"];
  $rappresentante = $_POST["insertRappresentante"];
  $inizio = $_POST["insertInizio"];
  $fine = $_POST["insertFine"];
  $scolastico = $_POST["insertScolastico"];
  $formatore = $_POST["formatoreSel"];
  $datore = $_POST["datoreSel"];
  $gruppo = $_POST["gruppoSel"];
  $sedeId = 0;

  // formattazione date da it a db + correzioni
  $inizio1 = explode('.', $inizio);
  if($inizio1[2]>31){
    $inizio1[2]=0000;
  }
  if($inizio1[1]>12){
    $inizio1[1]=00;
  }
  $inizio = $inizio1[2].'-'.$inizio1[1].'-'.$inizio1[0];

  $nascita1 = explode('.', $nascita);
  if($nascita1[2]>31){
    $nascita1[2]=0000;
  }
  if($nascita1[1]>12){
    $nascita1[1]=00;
  }
  $nascita = $nascita1[2].'-'.$nascita1[1].'-'.$nascita1[0];
  //echo "<script>alert('prova')</script>";
  // controlla se esiste la sede altrimenti aggiungila
  try{
    $query = $conn->prepare("SELECT sed_id AS 'id' from sede where sed_nome=:sede");
    $query->bindParam(':sede',$sede);
    $query->execute();
    if($query->rowCount()==0){
      try{
        $querySede = $conn->prepare("INSERT into sede(sed_nome) values(:sede)");
        $querySede->bindParam(':sede',$sede);
        $querySede->execute();
        // prendo il suo id
        $query1 = $conn->prepare("SELECT sed_id AS 'id' from sede where sed_nome=:sede");
        $query1->bindParam(':sede',$sede);
        $query1->execute();
        $row = $query1->fetch(PDO::FETCH_ASSOC);
        $sedeId=$row["id"];
      }
      catch(PDOException $e)
      {
        //echo $e;
      }
    }
    else{
      $row = $query->fetch(PDO::FETCH_ASSOC);
      $sedeId=$row["id"];
    }
  }
  catch(PDOException $e)
  {
    //echo $e;
  }
  // controllo se esiste già quell'apprendista
  try{
    $app = $conn->prepare("SELECT app_nome,app_flag AS 'flag' from apprendista where app_idContratto=:contratto && app_annoFine=:fine && app_annoScolastico=:scolastico");
    $app->bindParam(':contratto',$contratto);
    $app->bindParam(':scolastico',$scolastico);
    $app->bindParam(':fine',$fine);
    $app->execute();
    $row = $app->fetch(PDO::FETCH_ASSOC);
    //echo $row["flag"];
    // se è presente
    if($app->rowCount()==1){
      if($row["flag"]==0){ // ma nascosto, mostralo visibile
        try{
          $query=$conn->prepare("UPDATE apprendista SET
            app_nome=:nome,app_telefono=:telefono,app_dataNascita=:nascita,app_rappresentante=:rappresentante,
            app_statuto=:statuto,app_indirizzo=:indirizzo,app_domicilio=:domicilio,
            app_professione=:professione,app_flag=1,
            app_dataInizio=:inizio,grui_id=:gruppo,sed_id=:sede,dat_id=:datore,for_email=:formatore
            WHERE app_idContratto=:contratto && app_annoScolastico=:scolastico && app_annoFine=:fine;");

          $query->bindParam(':contratto',$contratto);
          $query->bindParam(':nome',$nome);
          $query->bindParam(':telefono',$telefono);
          $query->bindParam(':nascita',$nascita);
          $query->bindParam(':rappresentante',$rappresentante);
          $query->bindParam(':statuto',$statuto);
          $query->bindParam(':indirizzo',$indirizzo);
          $query->bindParam(':domicilio',$domicilio);
          $query->bindParam(':professione',$professione);
          $query->bindParam(':scolastico',$scolastico);
          $query->bindParam(':fine',$fine);
          $query->bindParam(':inizio',$inizio);
          $query->bindParam(':gruppo',$gruppo);
          $query->bindParam(':sede',$sedeId);
          $query->bindParam(':datore',$datore);
          $query->bindParam(':formatore',$formatore);
          $query->execute();
          // se non i sono problemi reindirizza alla pagina principale
          //echo "<script> location.href='apprendisti.php'</script>";
        }
        catch(PDOException $e)
        {
          //echo $e;
        }
      }
      else{ // altrimenti non fare nulla poiché vuol dire che è visibile
        echo  "<script>document.getElementById('errori').innerHTML='l apprendista esiste già quindi non è stato inserito';document.getElementById('errori').setAttribute('class','col-xs-6 alert alert-danger')</script>";
      }
    }
    // se invece non è presente crealo
    else{
      try{
        $query = $conn->prepare("INSERT INTO apprendista(app_idContratto, app_nome, app_telefono, app_dataNascita,
          app_rappresentante, app_statuto, app_indirizzo, app_domicilio,
          app_professione, app_annoScolastico, app_annoFine, app_dataInizio,
          grui_id, sed_id, dat_id, for_email) VALUES
          (:contratto,:nome,:telefono,:nascita,:rappresentante,:statuto
          ,:indirizzo,:domicilio,:professione,
          :scolastico,:fine,:inizio,:gruppo,:sede,
          :datore,:formatore)");
        $query->bindParam(':contratto',$contratto);
        $query->bindParam(':nome',$nome);
        $query->bindParam(':telefono',$telefono);
        $query->bindParam(':nascita',$nascita);
        $query->bindParam(':rappresentante',$rappresentante);
        $query->bindParam(':statuto',$statuto);
        $query->bindParam(':indirizzo',$indirizzo);
        $query->bindParam(':domicilio',$domicilio);
        $query->bindParam(':professione',$professione);
        $query->bindParam(':scolastico',$scolastico);
        $query->bindParam(':fine',$fine);
        $query->bindParam(':inizio',$inizio);
        $query->bindParam(':gruppo',$gruppo);
        $query->bindParam(':sede',$sedeId);
        $query->bindParam(':datore',$datore);
        $query->bindParam(':formatore',$formatore);
        $query->execute();
        //echo "<script> location.href='apprendisti.php'</script>";
      }
      catch(PDOException $e)
      {
        //  echo $e;
      }
    }
  }
  catch(PDOException $e)
  {
    //echo $e;
  }
}

// quando cerco di eliminare un apprendista
if(isset($_POST["apprendistaCancellato"]) AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
  $elimina = $_POST["apprendistaCancellato"];
  //echo "post: ".$elimina;
  $el = explode("/",$elimina);
  $contratto = $el[0];
  $scolastico = $el[1];
  $fine = $el[2];
  try{
    $query= $conn->prepare("UPDATE apprendista set app_flag=0  where app_idContratto=:contratto && app_annoScolastico=:scolastico && app_annoFine=:fine");
    $query->bindParam(':contratto',$contratto);
    $query->bindParam(':scolastico',$scolastico);
    $query->bindParam(':fine',$fine);
    $query->execute();
    echo "<script> location.href='apprendisti.php'</script>";
  }
  catch(PDOException $e)
  {
    //echo $e;
  }
}

// quando cerco di modificare un apprendista
if(isset($_POST["modifica"]) && isset($_POST["dati"]) AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
  $nome = $_POST["insertNome"];
  $nascita = $_POST["insertNascita"];
  $contratto = $_POST["insertContratto"];
  $statuto = $_POST["insertStatuto"];
  $indirizzo = $_POST["insertIndirizzo"];
  $domicilio = $_POST["insertDomicilio"];
  $telefono = $_POST["insertTelefono"];
  $professione = $_POST["insertProfessione"];
  $sede = $_POST["insertSede"];
  $rappresentante = $_POST["insertRappresentante"];
  $inizio = $_POST["insertInizio"];
  $fine = $_POST["insertFine"];
  $scolastico = $_POST["insertScolastico"];
  $formatore = $_POST["formatoreSel"];
  $datore = $_POST["datoreSel"];
  $gruppo = $_POST["gruppoSel"];
  $osservazioni = $_POST["osservazioni"];

  // cambio la formattazione delle date
  $inizio1 = explode('.', $inizio);
  $inizio = $inizio1[2].'-'.$inizio1[1].'-'.$inizio1[0];
  $nascita1 = explode('.', $nascita);
  $nascita = $nascita1[2].'-'.$nascita1[1].'-'.$nascita1[0];

  // controllo se la sede modificata esiste
  try{
    $query = $conn->prepare("SELECT sed_id AS 'id' from sede where sed_nome=:sede");
    $query->bindParam(':sede',$sede);
    $query->execute();
    // se non esiste l'aggiungo
    if($query->rowCount()==0){
      try{
        $querySede = $conn->prepare("INSERT into sede(sed_nome) values(:sede)");
        $querySede->bindParam(':sede',$sede);
        $querySede->execute();
        // e in seguito prendo il suo id
        $query1 = $conn->prepare("SELECT sed_id AS 'id' from sede where sed_nome=:sede");
        $query1->bindParam(':sede',$sede);
        $query1->execute();
        $row = $query1->fetch(PDO::FETCH_ASSOC);
        $sedeId=$row["id"];
      }
      catch(PDOException $e)
      {
        //echo $e;
      }
    }
    else{
      $row = $query->fetch(PDO::FETCH_ASSOC);
      $sedeId=$row["id"];
    }
  }
  catch(PDOException $e)
  {
    //echo $e;
  }

  // aggiorno l'apprendista
  try{
    $query=$conn->prepare("UPDATE apprendista SET
      app_nome=:nome,app_telefono=:telefono,app_dataNascita=:nascita,app_rappresentante=:rappresentante,
      app_statuto=:statuto,app_indirizzo=:indirizzo,app_domicilio=:domicilio,app_osservazioni=:osservazioni,
      app_professione=:professione,
      app_dataInizio=:inizio,grui_id=:gruppo,sed_id=:sede,dat_id=:datore,for_email=:formatore
      WHERE app_flag=1 && app_idContratto=:contratto && app_annoScolastico=:scolastico && app_annoFine=:fine;");

    $query->bindParam(':contratto',$contratto);
    $query->bindParam(':nome',$nome);
    $query->bindParam(':telefono',$telefono);
    $query->bindParam(':nascita',$nascita);
    $query->bindParam(':rappresentante',$rappresentante);
    $query->bindParam(':statuto',$statuto);
    $query->bindParam(':indirizzo',$indirizzo);
    $query->bindParam(':domicilio',$domicilio);
    $query->bindParam(':professione',$professione);
    $query->bindParam(':scolastico',$scolastico);
    $query->bindParam(':fine',$fine);
    $query->bindParam(':inizio',$inizio);
    $query->bindParam(':gruppo',$gruppo);
    $query->bindParam(':sede',$sedeId);
    $query->bindParam(':datore',$datore);
    $query->bindParam(':formatore',$formatore);
    $query->bindParam(':osservazioni',$osservazioni);
    $query->execute();
    // se non i sono problemi reindirizza alla pagina principale
    echo "<script> location.href='apprendisti.php'</script>";
  }
  catch(PDOException $e)
  {
    //echo $e;
  }
}
?>
