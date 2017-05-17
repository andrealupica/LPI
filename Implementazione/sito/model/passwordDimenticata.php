<?php
// pagina del login
	$user = "";
	$pass = "";
	// se il form è stato inviato
	if(isset($_POST["email"]) && isset($_POST["reemail"])){
		if(!empty($_POST["email"]) && !empty($_POST["reemail"])){
			$email = $_POST["email"];
			$reemail = $_POST["reemail"];
			// se le email sono uguali
			if($email == $reemail){
				try{
					// controllo che esista e in seguito invio un email con la password
					$query = $conn->prepare("SELECT ute_email from utente where ute_email=:email && ute_flag=1");
					$query->bindParam(':email',$email);
					$query->execute();
					if($query->rowCount()==1){
							$destinatario = $email;
							$oggetto = "richiesta cambio password";
							$message = "
							<html>
							<body>
							<p>Sei stato tu a voler cambiare password? In caso affermativo cliccare sul link altrimenti ignorare questa email</p>
							<a href='http://www.samtinfo.ch/i13lupand/web/lpi/confermaCambioPassword.php?email=".$email."'>cambia password</a>
							</body>
							</html>
							";
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
							$headers .= 'From: <webmaster@gaim.com>' . "\r\n";

							mail($destinatario,$oggetto,$message,$headers);
							echo  "<script>document.getElementById('messaggio').innerHTML='email inviata';document.getElementById('messaggio').setAttribute('class','col-xs-12 alert alert-success')</script>";

					}
					// avviso se non esiste
					else{
						echo  "<script>document.getElementById('messaggio').innerHTML='l email non è registrata al sito web';document.getElementById('messaggio').setAttribute('class','col-xs-12 alert alert-danger')</script>";

					}
				}
				catch(PDOException $e)
				{
					//echo  $e;
				}
			}
			// avviso se le email sono diverse
			else{
				echo  "<script>document.getElementById('messaggio').innerHTML='le due email non corrispondono';document.getElementById('messaggio').setAttribute('class','col-xs-12 alert alert-danger')</script>";
			}
		}
		else{
			echo  "<script>document.getElementById('messaggio').innerHTML='riempire entrambi i campi';document.getElementById('messaggio').setAttribute('class','col-xs-12 alert alert-warning ')</script>";

		}
	}
?>
