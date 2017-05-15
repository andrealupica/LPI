<!-- pagina creata per la modifica della password nel momento in cui si dimentica la password-->
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>password dimenticata</title>

	<script src="js/script.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/index.css" rel="stylesheet">
  <link href="css/stylebase.css" rel="stylesheet">

</head>
<body class="body">

	<div class="container contenitore">
		<div class="col-md-12">
			<div class="main-login main-center">
				<h1>Password Dimenticata</h1>
				<form class="form-horizontal" method="post" action="">
					<div class="form-group inputForm">
						<label for="email" class="cols-sm-2 control-label">Email:</label>
						<div class="cols-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
								<input type="email" class="form-control" name="email" id="email" required="required" placeholder="inserire la tua e-mail"/>
							</div>
						</div>
					</div>

					<div class="form-group inputForm">
						<label for="password" class="cols-sm-2 control-label">Ripeti Email:</label>
						<div class="cols-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
								<input type="email" class="form-control" name="reemail" id="reemail" required="required" placeholder="ripeti la tua email"/>
							</div>
						</div>
					</div>

					<div class="form-group btn-group btn-group-justified">
						<div class="col-xs-0 col-sm-1"></div>
						<div class="col-xs-6 col-sm-3">
	          	<button class="btn btn-primary col-xs-12">
	              	<span class="glyphicon glyphicon-send"></span> invia email
	          	</button>
						</div>
						<div class="col-xs-6 col-sm-8"><p class="help-block">invia una password di riserva alla tua email</p></div>
					</div>

        <label class="cols-sm-3 control-label" id="messaggio"></label>
				</form>
			</div>
		</div>
	</div>
</body>

</html>
