<?php
if(isset($_POST["insert"])  AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){ // dato che il submit viene effettuato con il controllo non serve il controllo del set di tutto
  $nome = $_POST["insertNome"];
  $email = $_POST["insertEmail"];
  $telefono = $_POST["insertTelefono"];
  $datore = $_POST["datoreSel"];
  try{
    $for = $conn->prepare("SELECT for_email from formatore where for_email=:email");
    $for->bindParam(':email',$email);
    $for->execute();
    if($for->rowCount()==0){
      try{
        $query = $conn->prepare("INSERT INTO formatore(for_email, for_nome,  for_telefono, dat_id)
                        VALUES (:email,:nome,:telefono,:datore)");
        $query->bindParam(':email',$email);
        $query->bindParam(':nome',$nome);
        $query->bindParam(':telefono',$telefono);
        $query->bindParam(':datore',$datore);
        if($query->execute()!=false){
          echo "<script> location.href='formatori.php'</script>";
        }
      }
      catch(PDOException $e)
      {
        echo $e;
      }
    }
    else{
      echo  "<script>document.getElementById('errori').innerHTML='il formatore esiste già';document.getElementById('errori').setAttribute('class','col-xs-6 alert alert-danger')</script>";
    }
  }
  catch(PDOException $e)
  {
    echo $e;
  }
}

if(isset($_POST["formatoreCancellato"]) AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
  $elimina = $_POST["formatoreCancellato"];
  //echo "post: ".$elimina;

  //echo $contratto.$scolastico.$fine;
  try{
    $query= $conn->prepare("UPDATE formatore set for_flag=0  where for_email=:email");
    $query->bindParam(':email',$elimina);
    $query->execute();
    echo "<script> location.href='formatori.php'</script>";
  }
  catch(PDOException $e)
  {
    echo $e;
  }
}

if(isset($_POST["modifica"]) && isset($_POST["dati"]) AND isset($_SESSION['email']) AND ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin")){
  $nome = $_POST["insertNome"];
  $email = $_POST["insertEmail"];
  $telefono = $_POST["insertTelefono"];
  $datore = $_POST["datoreSel"];
  $osservazioni = $_POST["osservazioni"];
  try{
  $query=$conn->prepare("UPDATE formatore SET for_nome=:nome,for_telefono=:telefono,dat_id=:datore,for_osservazioni=:osservazioni where for_email=:email;");
  $query->bindParam(':nome',$nome);
  $query->bindParam(':telefono',$telefono);
  $query->bindParam(':datore',$datore);
  $query->bindParam(':email',$email);
  $query->bindParam(':osservazioni',$osservazioni);
  $query->execute();
  echo "<script> location.href='formatori.php'</script>";
  }
  catch(PDOException $e)
  {
  echo $e;
  }

}

?>
