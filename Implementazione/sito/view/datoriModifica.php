<!-- pagina per la modifica dei datori-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null)  && ($_SESSION['tipo']=="master" OR $_SESSION['tipo']=="admin") && isset($_POST["modifica"])){
  //email
  $id = $_POST["modifica"];
  try{
    $query = $conn->prepare("SELECT dat_id AS 'id',dat_nome AS 'nome', dat_indirizzo AS 'indirizzo', dat_domicilio AS 'domicilio', dat_telefono AS 'telefono',
              dat_emailHR AS 'emailHR', dat_nomeHR AS 'nomeHR',dat_telefonoHR AS 'telefonoHR' from datore where dat_flag=1 and dat_id =:id");
    $query->bindParam(':id',$id);
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
    <title>Modifica Datori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/script.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link href="css/stylebase.css" rel="stylesheet">
  </head>
  <style>
  </style>
  <script>
  $(document).ready(function(){

    // quando clicco su salva
    $("#bSalva").click(function(){
      var n = 0;
      // svuota campi messaggio
      $("#messaggioNome").empty();
      $("#messaggioIndirizzo").empty();
      $("#messaggioDomicilio").empty();
      $("#messaggioTelefono").empty();
      $("#messaggioEmailHR").empty();
      $("#messaggioNomeHR").empty();
      $("#messaggioTelefonoHR").empty();

      // correzione dei bordi
      $("#insertNome").css("border","1px solid #ccc");
      $("#insertIndirizzo").css("border","1px solid #ccc");
      $("#insertDomicilio").css("border","1px solid #ccc");
      $("#insertTelefono").css("border","1px solid #ccc");
      $("#insertEmailHR").css("border","1px solid #ccc");
      $("#insertNomeHR").css("border","1px solid #ccc");
      $("#insertTelefonoHR").css("border","1px solid #ccc");

      // regex e creazioni variabili
      var regexTesto = /^[a-z A-Z]+$/;
      var regexDomicilio = /^(\d{4})\s([a-z ,A-Z])+$/;
      var regexAlfa = /^([0-9 a-z,A-Z])+$/;
      var regexTelefono = /^[+]?[\s 0-9 \s]+$/;
      var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

      nome = $("#insertNome").val();
      indirizzo = $("#insertIndirizzo").val();
      domicilio = $("#insertDomicilio").val();
      telefono = $("#insertTelefono").val();
      telefonoHR = $("#insertTelefonoHR").val();
      emailHR = $("#insertEmailHR").val();
      nomeHR = $("#insertNomeHR").val();

      if(regexAlfa.test(nome)){
        $("#messaggioNome").append("il formato va bene");
      }
      else{
        $("#messaggioNome").append("inserire solo lettere o numeri");
        $("#insertNome").css("border","1px solid red");
        n++;
      }

      if(regexAlfa.test(indirizzo)){
        $("#messaggioIndirizzo").append("il formato va bene");
      }
      else{
        $("#messaggioIndirizzo").append("inserire solo lettere o numeri");
        $("#insertIndirizzo").css("border","1px solid red");
        n++;
      }

      if(regexDomicilio.test(domicilio)){
        $("#messaggioDomicilio").append("il formato va bene");
      }
      else{
        $("#messaggioDomicilio").append("formato errato: CAP luogo");
        $("#insertDomicilio").css("border","1px solid red");
        n++;
      }

      if(regexTelefono.test(telefono)){
        $("#messaggioTelefono").append("il formato va bene");
      }
      else{
        $("#messaggioTelefono").append("inserire un numero di telefono valido");
        $("#insertTelefono").css("border","1px solid red");
        n++;
      }

      if(regexAlfa.test(nomeHR) || nomeHR ==""){
        $("#messaggioNomeHR").append("il formato va bene");
      }
      else{
        $("#messaggioNomeHR").append("inserire solo lettere o numeri");
        $("#insertNomeHR").css("border","1px solid red");
        n++;
      }

      if(regexEmail.test(emailHR) || emailHR ==""){
        $("#messaggioEmailHR").append("il formato va bene");
      }
      else{
        $("#messaggioEmailHR").append("inserire una email valida");
        $("#insertEmailHR").css("border","1px solid red");
        n++
      }

      if(regexTelefono.test(telefonoHR) || telefonoHR ==""){
        $("#messaggioTelefonoHR").append("il formato va bene");
      }
      else{
        $("#messaggioTelefonoHR").append("inserire un numero di telefono valido");
        $("#insertTelefonoHR").css("border","1px solid red");
        n++;
      }

      if(n==0){
        $("#formInsert").submit();
      }
      else{
      }
    });
  });

  </script>
  <body class="body">
    <?php include_once "menu.php";
      $row = $query->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="container">
      <h1 class="col-xs-12">Datori Modifica</h1>
      <br>
      <form id="formInsert" method="post">
        <div class="row">
          <div id="nomeDiv" class="col-xs-6">
            <label id="nomeLb">Nome datore:</label>
            <input type="text" name="insertNome" id="insertNome" class="form-control" value="<?php echo $row["nome"] ?>" placeholder="nome del datore" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioNome"></div>
          </div>
          <div id="telefonoDiv" class="col-xs-6">
            <label id="telefonoLb">Telefono:</label>
            <input type="text" name="insertTelefono" id="insertTelefono" class="form-control" value="<?php echo $row["telefono"] ?>"placeholder="es: 012 345 67 89" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioTelefono"></div>
          </div>
        </div>
        <div class="row">
          <div id="indirizzoDiv" class="col-xs-6">
            <label id="indirizzoLb">Indirizzo:</label>
            <input type="text" name="insertIndirizzo" id="insertIndirizzo" class="form-control" value="<?php echo $row["indirizzo"] ?>" placeholder="es: viale Filippo 15" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioIndirizzo"></div>
          </div>
          <div id="domicilioDiv" class="col-xs-6">
            <label id="domicilioLb">Domicilio:</label>
            <input type="text" name="insertDomicilio" id="insertDomicilio" class="form-control" value="<?php echo $row["domicilio"] ?>" placeholder="nnnn luogo" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioDomicilio"></div>
          </div>
        </div>
        <div class="row">
          <div id="nomeHRDiv" class="col-xs-6">
            <label id="nomeHRLb">Nome e cognome HR:</label>
            <input type="text" name="insertNomeHR" id="insertNomeHR" class="form-control" value="<?php echo $row["nomeHR"] ?>" placeholder="nome cognome" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioNomeHR"></div>
          </div>
          <div id="emailHRDiv" class="col-xs-6">
            <label id="emailHRLb">Email HR:</label>
            <input type="email" name="insertEmailHR" id="insertEmailHR" class="form-control" value="<?php echo $row["emailHR"] ?>" placeholder="email del HR" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioEmailHR"></div>
          </div>
        </div>
        <div class="row">
          <div id="telefonoHRDiv" class="col-xs-6">
            <label id="telefonoLb">Telefono HR:</label>
            <input type="text" name="insertTelefonoHR" id="insertTelefonoHR" class="form-control" value="<?php echo $row["telefonoHR"] ?>" placeholder="es: 012 345 67 89" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioTelefonoHR"></div>
          </div>
        </div>
        <input type="hidden" name="modifica" value="<?php echo $row["id"]?>"/>
        <input type="hidden" name="dati" value="dati"/>
      </form>
      <div class="col-xs-3"></div>
      <div class="col-xs-9">
        <button type="button" id="bSalva" class="btn btn-lg btn-default col-xs-4 col-xs-6">Salva</button>
      </div>
      <div class="col-xs-3" id="errori">
      </div>
    </div>
  </body>
  </html>
  <?php
}
else{
      echo "<script>location.href='datori.php'</script>";
}
?>
