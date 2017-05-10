<!-- pagina di login-->
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<script src="js/script.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/stylebase.css" rel="stylesheet">
</head>
<script>
$(document).ready(function(){

});
</script>
<body class="body">
	<div class="container contenitore">
		<div class="col-md-12">
			<div class="main-login main-center">
				<h1>Login</h1>
				<form class="form-horizontal" method="post" action="" id="form">
					<div class="form-group">
						<label for="email" class="cols-sm-3 control-label" id="labelemail">Username:</label>
						<div class="cols-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
								<input type="email" class="form-control" name="email" id="email" required="required" placeholder="inserire la tua e-mail" />
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="password" class="cols-sm-2 control-label" id="labelpassword">Password:</label>
						<div class="cols-sm-10">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
								<input type="password" class="form-control" name="password" id="password"  required="required" placeholder="Inserire la tua password" />
							</div>
						</div>
					</div>
          <div class="form-group btn-group btn-group-justified">
            <div class="col-xs-12 col-sm-6">
              <button class="btn btn-primary col-xs-12" id="user-login">
                <span class="glyphicon glyphicon-log-in"></span> Log-in
              </button>
            </div>
            <div class="col-xs-12 col-sm-6">
              <a class="btn btn-primary col-xs-12" href="passwordDimenticata.php">
                <span class="glyphicon glyphicon-question-sign"></span> Password Dimenticata
              </a>
            </div>
            <div class="col-xs-0 col-sm-1"></div>
            <div class="col-sm-3" id="messaggio">
            </div>
          </div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
