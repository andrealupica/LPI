<?php

// se si vuole eliminare l'email
if(isset($_POST["emailCancellata"]) AND isset($_SESSION['email'])){
  try{
    $email = $_POST['emailCancellata'];
    $query = $conn->prepare("UPDATE utente set ute_flag=0 where ute_email=:email");
    $query->bindParam(':email',$email);
    if($query->execute()!=false){
      echo "<script> location.href='gestioneAccount.php'</script>";
    }
  }
  catch(PDOException $e)
  {
    echo $e;
  }
}

if(isset($_POST["emailModificata"]) && isset($_SESSION['email'])){
  try{
    $email=$_POST["emailModificata"];
    if(isset($_POST["adminCheckbox"])){
      $query = $conn->prepare("UPDATE utente set ute_tipo=1 where ute_email=:email");
      $query->bindParam(':email',$email);
      $query->execute();
      echo "<script> location.href='gestioneAccount.php'</script>";
    }
    else{
      $query = $conn->prepare("UPDATE utente set ute_tipo=0 where ute_email=:email");
      $query->bindParam(':email',$email);
      $query->execute();
      echo "<script> location.href='gestioneAccount.php'</script>";
    }
    if(isset($_POST["password"]) && isset($_POST["repassword"]) && !empty($_POST["password"]) && !empty($_POST["repassword"]) && $_POST["password"]==$_POST["repassword"]){
      $query = $conn->prepare("UPDATE utente set ute_password=:password,ute_passwordTemp=1 where ute_email=:email");
      $query->bindParam(':email',$email);
      $query->bindParam(':password',md5($_POST["password"]));
      if($query->execute()!=false){
        $destinatario = $email;
        $oggetto = "cambio password da parte di un admin";
        $message = "<html><body><p>l'admin ".$_SESSION["email"]." ha cambiato le tue credenziali, ecco le tue nuove credenziali</p><p>email: ".$email."</p><p>password: ".$_POST["password"]."</p></body></html>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <webmaster@gaim.com>' . "\r\n";
        mail($destinatario,$oggetto,$message,$headers);
        echo "<script> location.href='gestioneAccount.php'</script>";
      }
    }
  }
  catch(PDOException $e)
  {
    echo $e;
  }
}

if(isset($_POST["emailInsert"]) AND !empty($_POST["emailInsert"]) AND isset($_SESSION['email'])){
  try{
    $email = $_POST['emailInsert'];
    $query = $conn->prepare("SELECT ute_email,ute_flag from utente where ute_email=:email");
    $query->bindParam(':email',$email);
    $query->execute();
    $password=randomPassword();
    $pass=md5($password);
    if($query->rowCount()==1){ // se è presente un dato nel db
      $row = $query->fetch(PDO::FETCH_ASSOC);
      if($row["ute_flag"]==0){ // se è presente nel db ma cancellato
        $flag = $conn->prepare("UPDATE utente set ute_flag=1, ute_passwordTemp=1, ute_tipo=0, ute_password=:password where ute_email=:email");
        $flag->bindParam(':email',$email);
        $flag->bindParam(':password',$pass);
        $flag->execute();
      }else{ // se è presente ma non cancellato
      }
    }
    else{ // crea nuovo account
      $query = $conn->prepare("INSERT into utente(ute_email,ute_password) values(:email,:password)");
      $query->bindParam(':email',$email);
      $query->bindParam(':password',$pass);
      if($query->execute()!=false){
        $destinatario = $email;
        $oggetto = "registrazione sito";
        $message = "<html><body><p>sei stato registrato al sito, ecco le tue nuove credenziali</p><p>email: ".$email."</p><p>password: ".$password."</p></body></html>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <webmaster@gaim.com>' . "\r\n";
        mail($destinatario,$oggetto,$message,$headers);
      }
    }
    echo "<script> location.href='gestioneAccount.php'</script>";
  }
	catch(PDOException $e)
	{
		echo $e;
	}
}

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for ($i = 0; $i < 10; $i++) {
        $n = rand(1, 62);
        $pass[$i] = $alphabet[$n-1];
    }
    return implode($pass);
}
?>
