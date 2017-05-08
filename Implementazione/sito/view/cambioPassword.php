<!-- pagina creata per la modifica della password nel momento in cui si dimentica la password-->
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cambio Password</title>

	<script src="js/script.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/index.css" rel="stylesheet">
  <link href="css/stylebase.css" rel="stylesheet">

</head>
<body class="body">
	<div class="container">
		<div class="col-md-12">
			<div class="main-login main-center">
				<h1>Cambio Password</h1>
				<form class="form-horizontal" method="post" action="">
					<div class="form-group inputForm">
						<label for="email" class="cols-sm-2 control-label">Password:</label>
						<div class="cols-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
								<input type="password" class="form-control" name="password" id="password" required="required" placeholder="inserire la tua nuova password"/>
							</div>
						</div>
					</div>

					<div class="form-group inputForm">
						<label for="password" class="cols-sm-2 control-label">Ripeti password:</label>
						<div class="cols-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
								<input type="password" class="form-control" name="repassword" id="repassword" required="required" placeholder="ripeti la tua nuova password"/>
							</div>
						</div>
					</div>
					<div class="form-group btn-group btn-group-justified" >
						<div class="col-xs-0 col-sm-2"></div>
						<div class="col-xs-6 col-sm-6">
	          	<button class="btn btn-primary col-xs-12">
	              	<span></span> cambia password
	          	</button>
						</div>
					</div>
        <label class="cols-sm-3 control-label" id="messaggio"></label>
				</form>
			</div>
		</div>
	</div>
</body>

</html>
