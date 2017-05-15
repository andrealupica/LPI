<?php
	// pagina del login
	$user = "";
	$pass = "";
	// se il form è settato
	if(isset($_POST["email"]) && isset($_POST["password"])){
		if(!empty($_POST["email"]) && !empty($_POST["password"])){
			$user = $_POST["email"];
			$user = mb_strtolower($user);
			$pass = md5($_POST["password"]);
			// controllo che l'utente esista e le credenziali di accesso siano corrette
			try{
	      $query = $conn->prepare("SELECT ute_email,ute_passwordTemp,ute_tipo from utente where ute_email=:email && ute_password=:password && ute_flag=1");
	      $query->bindParam(':email',$user);
	      $query->bindParam(':password',$pass);
	      $query->execute();
	      if($query->rowCount()==1){
	        $row = $query->fetch(PDO::FETCH_ASSOC);
	        // settaggio del email in sessione
	        $_SESSION["email"]=$user;
	        // settaggio del tipo in sessione
	        if($row["ute_tipo"]==0){
	          $_SESSION["tipo"]="normale";
	        }
	        else if($row["ute_tipo"]==1){
	          $_SESSION["tipo"]="admin";
	        }
	        else if($row["ute_tipo"]==2){
	          $_SESSION["tipo"]="master";
	        }
					// se la password è momentanea
	        if($row["ute_passwordTemp"]==1){
	          header("Location: cambioPassword.php");
	        }
	        else{
	          header("Location: apprendisti.php");
	        }
	      }
				// segnalo l errore
	      else{
	        echo  "<script>document.getElementById('messaggio').innerHTML='errore: impossibile effettuare il login, controlla le credenziali';document.getElementById('messaggio').setAttribute('class','col-xs-12 alert alert-danger')</script>";
	      }
			}
			catch(PDOException $e)
			{
				echo $e;
			}
		}
		else{
			echo  "<script>document.getElementById('messaggio').innerHTML='riempire entrambi i campi';document.getElementById('messaggio').setAttribute('class','col-xs-12 alert alert-warning')</script>";
		}
	}
?>
