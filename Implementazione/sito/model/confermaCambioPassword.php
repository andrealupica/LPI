<?php
  if(isset($_GET["email"])){
    include_once "connection.php";
    $email = $_GET["email"];
    try{
      $query = $conn->prepare("SELECT ute_email from utente where ute_email=:email && ute_flag=1");
      $query->bindParam(':email',$email);
      $query->execute();
      if($query->rowCount()==1){
        $password = randomPassword();
        $passdb = md5($password);
        $pass = $conn->prepare("UPDATE utente set ute_password=:password,ute_passwordTemp='1' where ute_email=:email && ute_flag='1'");
        $pass->bindParam(':password',$passdb);
        $pass->bindParam(':email',$email);
        if($pass->execute()!=false){
          $destinatario = $email;
          $oggetto = "richiesta cambio password confermata";
          $message = "<html><body><p>ecco le tue nuove credenziali</p><p>email: ".$email."</p><p>password: ".$password."</p></body></html>";
          $headers = "MIME-Version: 1.0" . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers .= 'From: <webmaster@gaim.com>' . "\r\n";

          mail($destinatario,$oggetto,$message,$headers);
          echo  "<script>document.getElementById('messaggio').innerHTML='nuova password inviata alla tua email'</script>";
        }
      }
    }
    catch(PDOException $e)
    {
      echo $e;
    }
  }
  // funzione per una password randomica
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for ($i = 0; $i < 10; $i++) {
        $n = rand(1, 62);
        $pass[$i] = $alphabet[$n-1];
    }
    return implode($pass);
}
?>