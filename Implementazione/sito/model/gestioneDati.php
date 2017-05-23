<?php

// quando si cerca di cancellare definitivamente un dato
  if(isset($_POST["cancellaDato"]) AND isset($_SESSION["email"]) AND $_SESSION['tipo']=="master"){
    echo $_POST["cancellaDato"];
    $dati = $_POST["cancellaDato"];
    $dato = explode('/',$dati); // separo il separatore dal dato che mi serve per eliminare

    // se si vuole eliminare un account
    if($dato[0] == "account"){
      try{
        $query = $conn->prepare("DELETE from utente where ute_email =:email");
        $query->bindParam(':email',$dato[1]);
        $query->execute();
        echo "<script> location.href='gestioneDati.php'</script>";
      }
      // se ritorna un errore vuol dire che è foreign key.
      catch(PDOException $e){
        echo  "<script>document.getElementById('errori').innerHTML='l account è presente in un altra tabella';document.getElementById('errori').setAttribute('class','col-xs-12 alert alert-warning')</script>";
      //  echo $e;
      }
    }

    // se si vuole eliminare un apprendista
    if($dato[0] == "apprendista"){
      echo $dato[1];
      $dum = explode("-",$dato[1]);
      echo "<br>con".$dum[0]." fine".$dum[1]." sco".$dum[2];
      try{
        $query = $conn->prepare("DELETE from apprendista where app_idContratto=:contratto AND app_annoFine=:fine AND app_annoScolastico=:scolastico");
        $query->bindParam(':contratto',$dum[0]);
        $query->bindParam(':fine',$dum[1]);
        $query->bindParam(':scolastico',$dum[2]);
        $query->execute();
        echo "<script> location.href='gestioneDati.php'</script>";
      }
      // se ritorna un errore vuol dire che è foreign key, non dovrebbe accadere.
      catch(PDOException $e){
        echo  "<script>document.getElementById('errori').innerHTML='l apprendista è presente in un altra tabella';document.getElementById('errori').setAttribute('class','col-xs-12 alert alert-warning')</script>";
        //echo $e;
      }
    }

    // se si vuole eliminare un formatore
    if($dato[0] == "formatore"){
      try{
        $query = $conn->prepare("DELETE from formatore where for_email =:email");
        $query->bindParam(':email',$dato[1]);
        $query->execute();
        echo "<script> location.href='gestioneDati.php'</script>";
      }
      // se ritorna un errore vuol dire che è foreign key.
      catch(PDOException $e){
        echo  "<script>document.getElementById('errori').innerHTML='il formatore è presente in un altra tabella,';document.getElementById('errori').setAttribute('class','col-xs-12 alert alert-warning')</script>";
        //echo $e;
      }
    }

    // se si vuole eliminare un datore
    if($dato[0] == "datore"){
      try{
        $query = $conn->prepare("DELETE from datore where dat_id =:id");
        $query->bindParam(':id',$dato[1]);
        $query->execute();
        echo "eseguito";
        echo "<script> location.href='gestioneDati.php'</script>";
      }
      // se ritorna un errore vuol dire che è foreign key.
      catch(PDOException $e){
        echo  "<script>document.getElementById('errori').innerHTML='il datore è presente in un altra tabella';document.getElementById('errori').setAttribute('class','col-xs-12 alert alert-warning')</script>";
        //echo $e;
      }
    }

    // se si vuole eliminare un gruppo di email
    if($dato[0] == "gruppoEmail"){
      try{
        $query = $conn->prepare("DELETE from gruefor where grue_id =:id");
        $query->bindParam(':id',$dato[1]);
        $query->execute();
        $query = $conn->prepare("DELETE from gruppoEmail where grue_id =:id");
        $query->bindParam(':id',$dato[1]);
        $query->execute();
        echo "<script> location.href='gestioneDati.php'</script>";
      }
      // se ritorna un errore vuol dire che c'è un errore, l'eliminazione dovrebbe poter avvenire dato che è stata eliminata la fk.
      catch(PDOException $e){
        echo $e;
      }
    }

    // se si vuole eliminare un email
    if($dato[0] == "email"){
      try{
        // prima elimino l email-formatore
        $query = $conn->prepare("DELETE from forema where ema_id =:id");
        $query->bindParam(':id',$dato[1]);
        $query->execute();
        // in seguito l'email vera e propria
        $query = $conn->prepare("DELETE from email where ema_id =:id");
        $query->bindParam(':id',$dato[1]);
        $query->execute();
        echo "<script> location.href='gestioneDati.php'</script>";
      }
      // se ritorna un errore vuol dire che c'è un errore, l'eliminazione dovrebbe poter avvenire.
      catch(PDOException $e){
        echo $e;
      }
    }
  }

  // quando si cerca di ripristinarlo
  if(isset($_POST["ripristinaDato"]) AND isset($_SESSION["email"]) AND $_SESSION['tipo']=="master"){
    $dati = $_POST["ripristinaDato"];
    $dato = explode('/',$dati);
    //echo "dati: ".$dato[0]." ".$dato[1];

    // se si vuole ripristinare un account
    if($dato[0] == "account"){
      try{
        $query = $conn->prepare("UPDATE utente set ute_flag=1 where ute_email =:email");
        $query->bindParam(':email',$dato[1]);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }
    }

    // se si vuole ripristinare un apprendista
    if($dato[0] == "apprendista"){
      $dum = explode("-",$dato[1]);
      try{
        $query = $conn->prepare("UPDATE apprendista set app_flag=1 where app_idContratto =:contratto AND app_annoFine =:fine AND app_annoScolastico =:scolastico");
        $query->bindParam(':contratto',$dum[0]);
        $query->bindParam(':fine',$dum[1]);
        $query->bindParam(':scolastico',$dum[2]);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }
    }

    // se si vuole ripristinare un formatore
    if($dato[0] == "formatore"){
      try{
        $query = $conn->prepare("UPDATE formatore set for_flag=1 where for_email =:email");
        $query->bindParam(':email',$dato[1]);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }
    }

    // se si vuole ripristinare un datore
    if($dato[0] == "datore"){
      try{
        $query = $conn->prepare("UPDATE datore set dat_flag=1 where dat_id =:id");
        $query->bindParam(':id',$dato[1]);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }
    }

    // se si vuole ripristinare un gruppoEmail
    if($dato[0] == "gruppoEmail"){
      try{
        $query = $conn->prepare("UPDATE gruppoEmail set grue_flag=1 where grue_id =:id");
        $query->bindParam(':id',$dato[1]);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }
    }

    // se si vuole ripristinare un gruppoEmail
    if($dato[0] == "email"){
      try{
        $query = $conn->prepare("UPDATE email set ema_flag=1 where ema_id =:id");
        $query->bindParam(':id',$dato[1]);
        $query->execute();
      }
      catch(PDOException $e){
        echo $e;
      }
    }
    echo "<script> location.href='gestioneDati.php'</script>";

  }
?>
