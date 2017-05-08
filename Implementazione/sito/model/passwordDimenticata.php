<?php
// pagina del login
	$user = "";
	$pass = "";
	if(isset($_POST["email"]) && isset($_POST["reemail"])){
		if(!empty($_POST["email"]) && !empty($_POST["reemail"])){
			$email = $_POST["email"];
			$reemail = $_POST["reemail"];
			if($email == $reemail){
				try{
					$query = $conn->prepare("SELECT ute_email from utente where ute_email=:email && ute_flag=1");
					$query->bindParam(':email',$email);
					$query->execute();
					if($query->rowCount()==1){
							$destinatario = $email;
							$oggetto = "richiesta cambio password";
							$message = "
							<html>
							<body>
							<p>Sei stato tu a voler cambiare password?in caso affermativo cliccare sul link altrimenti ignorare questa email</p>
							<a href='http://www.samtinfo.ch/i13lupand/web/lpi/confermaCambioPassword.php?email=".$email."'>cambia password</a>
							</body>
							</html>
							";

							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
							$headers .= 'From: <webmaster@gaim.com>' . "\r\n";

							mail($destinatario,$oggetto,$message,$headers);
							echo  "<script>document.getElementById('messaggio').innerHTML='email inviata';document.getElementById('messaggio').setAttribute('class','control-label alert alert-success')</script>";

					}
					else{
						echo  "<script>document.getElementById('messaggio').innerHTML='l email non è registrata al sito web';document.getElementById('messaggio').setAttribute('class','control-label alert alert-danger')</script>";

					}
				}
				catch(PDOException $e)
				{
					echo  $e;
				}
			}
			else{
				echo  "<script>document.getElementById('messaggio').innerHTML='le due email non corrispondono';document.getElementById('messaggio').setAttribute('class','control-label alert alert-danger')</script>";
			}
		}
		else{
			echo  "<script>document.getElementById('messaggio').innerHTML='riempire entrambi i campi';document.getElementById('messaggio').setAttribute('class','control-label alert alert-warning ')</script>";

		}
	}
?>
