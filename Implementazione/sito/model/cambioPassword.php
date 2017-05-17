<?php
// pagina del cambio password
	$user = "";
	$pass = "";
	// se il form è stato inviato in post
	if(isset($_POST["password"]) && isset($_POST["repassword"]) && isset($_SESSION["email"])){
		if(!empty($_POST["password"]) && !empty($_POST["repassword"])){
			$password = $_POST["password"];
			$repassword = $_POST["repassword"];
			// controlla le password uguali
			if($password == $repassword){
				try{
					$password=md5($password);
					$email = $_SESSION["email"];
					$query = $conn->prepare("UPDATE utente set ute_password=:password,ute_passwordTemp=0 where ute_email=:email && ute_flag=1");
					$query->bindParam(':password',$password);
					$query->bindParam(':email',$email);
					// se non da errore faccio riefettuare l'accesso
					if($query->execute()!=false){
            header("Location: logout.php");
					}
				}
				catch(PDOException $e)
				{
					//echo  $e;
				}
			}
			else{
				echo  "<script>document.getElementById('messaggio').innerHTML='le due password non corrispondono';document.getElementById('messaggio').setAttribute('class','col-xs-12 alert alert-danger')</script>";
			}
		}
		else{
			echo  "<script>document.getElementById('messaggio').innerHTML='riempire entrambi i campi';document.getElementById('messaggio').setAttribute('class','col-xs-12 alert alert-warning')</script>";
		}
	}
?>
