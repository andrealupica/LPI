<?php
// pagina del login
	$user = "";
	$pass = "";
	if(isset($_POST["password"]) && isset($_POST["repassword"])){
		if(!empty($_POST["password"]) && !empty($_POST["repassword"])){
			$password = $_POST["password"];
			$repassword = $_POST["repassword"];
			if($password == $repassword){
				try{
					$password=md5($password);
					$email = $_SESSION["email"];
					$query = $conn->prepare("UPDATE utente set ute_password=:password,ute_passwordTemp=0 where ute_email=:email && ute_flag=1");
					$query->bindParam(':password',$password);
					$query->bindParam(':email',$email);
					if($query->execute()!=false){
            header("Location: logout.php");
					}
				}
				catch(PDOException $e)
				{
					echo  $e;
				}
			}
			else{
				echo  "<script>document.getElementById('messaggio').innerHTML='le due password non corrispondono';document.getElementById('messaggio').setAttribute('class','control-label alert alert-danger')</script>";
			}
		}
		else{
			echo  "<script>document.getElementById('messaggio').innerHTML='riempire entrambi i campi';document.getElementById('messaggio').setAttribute('class','control-label alert alert-warning')</script>";
		}
	}
?>
