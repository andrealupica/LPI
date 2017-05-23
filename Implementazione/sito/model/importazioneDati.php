<?php
// per anno di inserimento
if(isset($_POST["Import"]) && !empty($_POST["Import"]) AND isset($_SESSION['email']) AND ($_SESSION["tipo"]=="master" OR $_SESSION["tipo"]=="admin")){
  if ($_FILES["idCSV"]["size"] > 0) {
   $grandezz=0;
   $file = $_FILES['idCSV']['tmp_name'];
   $handle = fopen($file,"r");
   $data = null;
   fgets($handle);

   // per anno di inserimento
   $anno = date(Y);
   if(date(m)<7){ // se il mese è prima di agosto allora l'anno è quello precedente
     $anno = $anno -1;
   }
   //echo $anno." ".date(m);
   // finché non finisce il file
   while (($getData = fgetcsv($handle,10000,";")) != FALSE){
     // creazione delle variabili
     $apprendista = ucwords(strtolower(utf8_encode($getData[0]))); // cognome nome apprendista
     $nascitaApprendista = $getData[1]; // data di nascita apprendista
     $dum = explode('.', $nascitaApprendista);
     $nascitaApprendista = $dum[2].'-'.$dum[1].'-'.$dum[0]; //  formattato per db
     $sede = ucwords(strtolower(utf8_encode($getData[2]))); // sede scolastica e località
     $indirizzoApprendista = ucwords(strtolower(utf8_encode($getData[3]))); // indirizzo apprendista
     $domicilioApprendista = ucwords(strtolower(utf8_encode($getData[4]))); // domicilio apprendista
     $telefonoApprendista = $getData[5]; // Tel. priv. allievo
     $datore = ucwords(strtolower(utf8_encode($getData[6]))); // Nome datore lavoro
     $indirizzoDatore = ucwords(strtolower(utf8_encode($getData[7]))); // Indirizzo postale datore lavoro
     $domicilioDatore = ucwords(strtolower(utf8_encode($getData[8]))); // CAP - Località postale datore lavoro
     $telefonoDatore = $getData[9]; // Datore lavoro tel professione
     $fineContratto = $getData[10]; // Anno fine contratto
     $inizioContratto = $getData[11]; // Data inizio contratto professione
     $dum = explode('.', $inizioContratto);
     $inizioContratto = $dum[2].'-'.$dum[1].'-'.$dum[0]; //  formattato per db
     $annoScolastico = $getData[12]; // Anno scolastico apprendista
     $dum = explode(' ', $annoScolastico);
     $annoScolastico = $dum[0]; //  formattato per db
     $professione =strtolower(utf8_encode($getData[13])); // Professione apprendista
     $contratto = $getData[14]; // Numero contratto
     if(strlen($contratto)!=9){ // se manca lo 0 finale
       $contratto .="0";
     }
     $statuto = $getData[15]; // Statuto contratto
     $rappresentante = ucwords(strtolower(utf8_encode($getData[16]))); // Cognome nome rappresentante
     $formatore = ucwords(strtolower(utf8_encode($getData[17]))); // formatore
     $emailFormatore = strtolower($getData[18]); // Datore lavoro email
      // il telefono del formatore non è presente nel file csv

     ################################################################################### gestione della sede

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
       // altrimenti prendo semplicemente il suo id
       else{
         $row = $query->fetch(PDO::FETCH_ASSOC);
         $sedeId=$row["id"];
       }
     }
     catch(PDOException $e)
     {
       //echo $e;
     }

     ################################################################################## gestione gruppo

     try{
       $query = $conn->prepare("SELECT grui_id AS 'id' from gruppoInserimento where grui_nome=:gruppo");
       $query->bindParam(':gruppo',$anno);
       $query->execute();
       // se non esiste l'aggiungo
       if($query->rowCount()==0){
         try{
           $queryGruppo = $conn->prepare("INSERT into gruppoInserimento(grui_nome) values(:nome)");
           $queryGruppo->bindParam(':nome',$anno);
           $queryGruppo->execute();
           // e in seguito prendo il suo id
           $query1 = $conn->prepare("SELECT grui_id AS 'id' from gruppoInserimento where grui_nome=:nome");
           $query1->bindParam(':nome',$anno);
           $query1->execute();
           $row = $query1->fetch(PDO::FETCH_ASSOC);
           $gruppoId=$row["id"];
         }
         catch(PDOException $e)
         {
           //echo $e;
         }
       }
       else{ // altrimenti prendo direttamente il suo id
         $row = $query->fetch(PDO::FETCH_ASSOC);
         $gruppoId=$row["id"];
       }
     }
     catch(PDOException $e)
     {
       //echo $e;
     }

     ################################################################################### inserimento datore
     //echo "dati: ".$datore." ".$indirizzoDatore." ".$domicilioDatore." ".$telefonoDatore."<br>";

     try{
       // seleziono i formatori con quell email
       $dat = $conn->prepare("SELECT dat_id,dat_flag AS 'flag' from datore where dat_nome=:nome");
       $dat->bindParam(':nome',$datore);
       $dat->execute();
       $row = $dat->fetch(PDO::FETCH_ASSOC);
       // controllo che esiste nel db
       if($dat->rowCount()==1){
         if($row["flag"]==0){ // se nascosto, mostralo visibile
           try{
             $query=$conn->prepare("UPDATE datore SET dat_indirizzo =:indirizzo,dat_domicilio =:domicilio,dat_telefono =:telefono,
                                     dat_flag=1,dat_emailHR=:emailHR,dat_nomeHR =:nomeHR, dat_telefonoHR=:telefonoHR
                                      where dat_nome=:nome");
             $query->bindParam(':nome',$datore);
             $query->bindParam(':indirizzo',$indirizzoDatore);
             $query->bindParam(':domicilio',$domicilioDatore);
             $query->bindParam(':telefono',$telefonoDatore);
             $query->bindParam(':emailHR',$emailHR);
             $query->bindParam(':nomeHR',$nomeHR);
             $query->bindParam(':telefonoHR',$telefonoHR);
             $query->execute();
           }
           catch(PDOException $e)
           {
           //echo $e;
           }
         }
         else{
           //echo  "<script>document.getElementById('errori').innerHTML='il datore esiste già';document.getElementById('errori').setAttribute('class','col-xs-6 alert alert-danger')</script>";
         }
       }
       // se non esiste lo aggiungo
       else{
         try{
           $query = $conn->prepare("INSERT INTO datore(dat_nome, dat_indirizzo,dat_domicilio,dat_telefono,dat_emailHR,dat_nomeHR,dat_telefonoHR)
                                   values(:nome,:indirizzo,:domicilio,:telefono,:emailHR,:nomeHR,:telefonoHR)");
           $query->bindParam(':nome',$datore);
           $query->bindParam(':indirizzo',$indirizzoDatore);
           $query->bindParam(':domicilio',$domicilioDatore);
           $query->bindParam(':telefono',$telefonoDatore);
           $query->bindParam(':emailHR',$emailHR);
           $query->bindParam(':nomeHR',$nomeHR);
           $query->bindParam(':telefonoHR',$telefonoHR);
           // se non ci sono stati errori ricarico la pagina
           if($query->execute()!=false){
             //echo "<script> location.href='datori.php'</script>";
           }
         }
         catch(PDOException $e)
         {
           //echo $e;
         }
       }
     }
     catch(PDOException $e)
     {
       //echo $e;
     }

     ##################### seleziono l'id del datore, il datore esiste per forza dato che se non esisteva per cascata viene inserito prima del formatore e dell apprendista

     $dat = $conn->prepare("SELECT dat_id AS 'id' from datore where dat_nome=:nome");
     $dat->bindParam(':nome',$datore);
     $dat->execute();
     $row = $dat->fetch(PDO::FETCH_ASSOC);
     $datoreId = $row["id"];

     ################################################################################### inserimento formatore

     try{
       // seleziono i formatori con quell email
       $for = $conn->prepare("SELECT for_email,for_flag AS 'flag' from formatore where for_email=:email");
       $for->bindParam(':email',$emailFormatore);
       $for->execute();
       // controllo se è presente sul db
       if($for->rowCount()==1){
         if($row["flag"]==0){ // se nascosto, mostralo visibile
           try{
             $query=$conn->prepare("UPDATE formatore SET for_nome=:nome,for_telefono=:telefono,dat_id=:datore,for_flag=1 where for_email=:email;");
             $query->bindParam(':nome',$formatore);
             $query->bindParam(':telefono',$telefono);
             $query->bindParam(':datore',$datoreId);
             $query->bindParam(':email',$emailFormatore);
             $query->execute();
             //echo "<script> location.href='formatori.php'</script>";
           }
           catch(PDOException $e)
           {
             //echo $e;
           }
         }
         else{  // altrimenti avviso della ripetizione
          // echo  "<script>document.getElementById('errori').innerHTML='il formatore esiste già';document.getElementById('errori').setAttribute('class','col-xs-6 alert alert-danger')</script>";
         }
       }
       // se non c'è lo aggiungo
       else{
         try{
           $query = $conn->prepare("INSERT INTO formatore(for_email, for_nome,  for_telefono, dat_id)
                           VALUES (:email,:nome,:telefono,:datore)");
           $query->bindParam(':email',$emailFormatore);
           $query->bindParam(':nome',$formatore);
           $query->bindParam(':telefono',$telefono);
           $query->bindParam(':datore',$datoreId);
           // se non ci sono stati errori ricarico la pagina
           if($query->execute()!=false){
            // echo "<script> location.href='formatori.php'</script>";
           }
         }
         catch(PDOException $e)
         {
           //echo $e;
         }
       }

     }
     catch(PDOException $e)
     {
       //echo $e;
     }


     ################################################################################### inserimento apprendisti


     try{
       $app = $conn->prepare("SELECT app_nome,app_flag AS 'flag' from apprendista where app_idContratto=:contratto && app_annoFine=:fine && app_annoScolastico=:scolastico");
       $app->bindParam(':contratto',$contratto);
       $app->bindParam(':scolastico',$annoScolastico);
       $app->bindParam(':fine',$fineContratto);
       $app->execute();
       $row = $app->fetch(PDO::FETCH_ASSOC);
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
             $query->bindParam(':nome',$apprendista);
             $query->bindParam(':telefono',$telefonoApprendista);
             $query->bindParam(':nascita',$nascitaApprendista);
             $query->bindParam(':rappresentante',$rappresentante);
             $query->bindParam(':statuto',$statuto);
             $query->bindParam(':indirizzo',$indirizzoApprendista);
             $query->bindParam(':domicilio',$domicilioApprendista);
             $query->bindParam(':professione',$professione);
             $query->bindParam(':scolastico',$annoScolastico);
             $query->bindParam(':fine',$fineContratto);
             $query->bindParam(':inizio',$inizioContratto);
             $query->bindParam(':gruppo',$gruppoId);
             $query->bindParam(':sede',$sedeId);
             $query->bindParam(':datore',$datoreId);
             $query->bindParam(':formatore',$emailFormatore);
             $query->execute();
             // se non i sono problemi reindirizza alla pagina principale
           }
           catch(PDOException $e)
           {
             //echo $e;
           }
         }
         else{ // altrimenti non fare nulla poiché vuol dire che è visibile
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
            /* echo "INSERT INTO apprendista(app_idContratto, app_nome, app_telefono, app_dataNascita,
               app_rappresentante, app_statuto, app_indirizzo, app_domicilio,
               app_professione, app_annoScolastico, app_annoFine, app_dataInizio,
               grui_id, sed_id, dat_id, for_email) VALUES
               ($contratto,$apprendista,$telefonoApprendista,$nascitaApprendista,$rappresentante,$statuto
               ,$indirizzoApprendista,$domicilioApprendista,$professione,
               $annoScolastico,$fineContratto,$inizioContratto,$gruppoId,$sedeId,
               $datoreId,$emailFormatore)<br>";*/
           $query->bindParam(':contratto',$contratto);
           $query->bindParam(':nome',$apprendista);
           $query->bindParam(':telefono',$telefonoApprendista);
           $query->bindParam(':nascita',$nascitaApprendista);
           $query->bindParam(':rappresentante',$rappresentante);
           $query->bindParam(':statuto',$statuto);
           $query->bindParam(':indirizzo',$indirizzoApprendista);
           $query->bindParam(':domicilio',$domicilioApprendista);
           $query->bindParam(':professione',$professione);
           $query->bindParam(':scolastico',$annoScolastico);
           $query->bindParam(':fine',$fineContratto);
           $query->bindParam(':inizio',$inizioContratto);
           $query->bindParam(':gruppo',$gruppoId);
           $query->bindParam(':sede',$sedeId);
           $query->bindParam(':datore',$datoreId);
           $query->bindParam(':formatore',$emailFormatore);
           $query->execute();
         }
         catch(PDOException $e)
         {
           //echo $e;
         }
       }
     }
     catch(PDOException $e)
     {
      // echo $e;
     }
   }
    fclose($handle);
    #################################################################### dopo aver chiusto il file lo salvo sul server e salvo il nome nel db

    // carico il file sul server
    $target_dir = "uploads/";
    $target_file = $target_dir.date("d-m-Y_H-i-s")."-".basename($_FILES["idCSV"]["name"]);
    move_uploaded_file($_FILES['idCSV']['tmp_name'], $target_file);

    try{
      // salvo nel db il nome del file e chi lo ha importato
      $query = $conn->prepare("INSERT into file_(fil_nome,ute_email) values(:nome,:email)");
      $nome = date("d-m-Y_H-i-s")."-".basename($_FILES["idCSV"]["name"]);
      $query->bindParam(':nome',$nome);
      $query->bindParam(':email',$_SESSION["email"]);
      $query->execute();
    }
    catch(PDOException $e){
      //echo $e;
    }
    // ricarico la pagina
    echo "<script> location.href='gestioneCSV.php'</script>";
  }
}


?>
