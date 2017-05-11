<!-- pagina per la gestione dei formatori-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null)  && isset($_POST["modifica"])){
  $mod = $_POST["modifica"];
  try{
    $query = $conn->prepare("SELECT form.for_email AS 'email',form.for_nome AS 'nome', form.for_telefono AS 'telefono',
      dat.dat_nome AS 'datore',dat.dat_id AS 'dat_id',form.for_osservazioni AS 'osservazioni',
      dat.dat_emailHR AS 'emailHR',dat.dat_nomeHR AS 'nomeHR',dat.dat_telefonoHR AS 'telefonoHR' from formatore form
      join datore dat ON form.dat_id=dat.dat_id where form.for_flag=1 and dat.dat_flag=1 and form.for_email =:email");
    $query->bindParam(':email',$mod);
    $query->execute();
  }
  catch(PDOException $e)
  {
    echo $e;
  }
  ?>
  <!DOCTYPE html>
  <html lang="it">
  <head>
    <title>Formatori Modifica</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/script.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link href="css/stylebase.css" rel="stylesheet">
  </head>
  <style>
  </style>
  <body class="body" style="min-width:680px;">
    <?php include_once "menu.php";
      $row = $query->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="container">
      <h1 class="col-xs-12">Formatori Modifica</h1>
      <br>
      <div id="datoreDiv" class="col-md-4 col-xs-6">
        <label id="nomeLb">Datore:</label>
        <input type="text" name="insertDatore" id="insertDatore" readonly="true"class="form-control" value="<?php echo $row["datore"]?> "placeholder="" required="required"/>
        <div class="col-xs-12 messaggio" id="messaggioDatore"></div>
      </div>
      <div id="HRDiv" class="col-md-4 col-xs-6">
        <label id="nomeLb">Cognome nome HR:</label>
        <input type="text" name="insertHR" id="insertHR" readonly="true"class="form-control" value="<?php echo $row["nomeHR"]?> "placeholder="" required="required"/>
        <div class="col-xs-12 messaggio" id="messaggioHR"></div>
      </div>
      <div id="emailHRDiv" class="col-md-4 col-xs-6">
        <label id="nomeLb">Email HR:</label>
        <input type="text" name="insertEmailHR" id="insertEmailHR" readonly="true"class="form-control" value="<?php echo $row["emailHR"]?> "placeholder="" required="required"/>
        <div class="col-xs-12 messaggio" id="messaggioEmailHR"></div>
      </div>
      <div id="telefonoHRDiv" class="col-md-4 col-xs-6">
        <label id="nomeLb">Telefono HR:</label>
        <input type="text" name="insertTelefonoHR" id="insertTelefonoHR" readonly="true"class="form-control" value="<?php echo $row["telefonoHR"]?> "placeholder="" required="required"/>
        <div class="col-xs-12 messaggio" id="messaggioTelefonoHR"></div>
      </div>
      <div id="nomeDiv" class="col-md-4 col-xs-6">
        <label id="nomeLb">Nome e cognome:</label>
        <input type="text" name="insertNome" id="insertNome" readonly="true"class="form-control" value="<?php echo $row["nome"]?> "placeholder="nome cognome" required="required"/>
        <div class="col-xs-12 messaggio" id="messaggioNome"></div>
      </div>
      <div id="emailDiv" class="col-md-4 col-xs-6">
        <label id="emailLb">Email:</label>
        <input type="email" name="insertEmail" id="insertEmail" readonly="true" class="form-control" value="<?php echo $row["email"] ?>" placeholder="email del formatore" required="required"/>
        <div class="col-xs-12 messaggio" id="messaggioEmail"></div>
      </div>
      <div id="telefonoDiv" class="col-md-4 col-xs-6">
        <label id="telefonoLb">Telefono:</label>
        <input type="text" name="insertTelefono" id="insertTelefono" readonly="true" class="form-control" value="<?php echo $row["telefono"] ?>" placeholder="es: 012 345 67 89" required="required"/>
        <div class="col-xs-12 messaggio" id="messaggioTelefono"></div>
      </div>
      <div class="col-xs-12">
        <label id="osservazioniLb">Osservazioni:</label>
        <textarea class="form-control" readonly="true"name="osservazioni" style="margin-top:0px" ><?php echo $row["osservazioni"]?>
        </textarea>
        <div class="col-xs-12 messaggio" id=""></div>
      </div>
    </div>
  </body>
  </html>
  <?php
}
else{
      echo "<script>location.href='formatori.php'</script>";
}
?>
