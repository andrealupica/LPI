<!-- pagina per la gestione degli apprendisti-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null) && isset($_POST["modifica"])){ // da riguardare
  $modifica = $_POST["modifica"];
  //echo "post: ".$elimina;
  $m = explode("/",$modifica);
  $contratto = $m[0];
  $scolastico = $m[1];
  $fine = $m[2];
  try{
    $query = $conn->prepare("SELECT app.app_idContratto AS 'contratto', app.app_nome AS 'nome', app.app_telefono AS 'telefono', app.app_dataNascita AS 'nascita',
      app.app_rappresentante AS 'rappresentante', app.app_statuto AS 'statuto', app.app_indirizzo AS 'indirizzo', app.app_domicilio AS 'domicilio',
      app.app_professione AS 'professione',  app.app_annoScolastico AS 'annoScolastico', app.app_annoFine AS 'annoFine', app.app_dataInizio AS 'dataInizio',
      grui_id AS 'gruppo', sed.sed_nome AS 'sede', dat.dat_nome AS 'datore', form.for_nome AS 'formatore' FROM apprendista app
      JOIN datore dat ON dat.dat_id = app.dat_id
      JOIN formatore form ON form.for_email = app.for_email
      JOIN sede sed ON sed.sed_id = app.sed_id
      WHERE app.app_flag=1 && app.app_idContratto=:contratto && app.app_annoScolastico=:scolastico && app.annoFine=:fine;");
    $query->bindParam(':contratto',$contratto);
    $query->bindParam(':scolastico',$scolastico);
    $query->bindParam(':fine',$fine);
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
    <title>Apprendisti modifica</title>
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

// quando il valore del select del datore cambia
    $("#datoreSel").change(function(){
      valore=$("#datoreSel").val();
        $.ajax({
          type:"POST",
          url: "model/apprendisti2.php",
          data:{datoreSel:valore},
          success: function(result){
            result=JSON.parse(result);
            alert(result);
            $("#formatoreSel").find("option").remove();
            for (var i = 0; i < result.length; i++) {
              risultato = result[i].split("/");
              $("#formatoreSel").append("<option value='"+risultato[1]+"'>"+risultato[0]+"</option>")
            }
        }});
    });

    $("#bInsert").click(function(){
      var n = 0;
      // regex e creazioni variabili
      var regexTesto = /[a-z,A-Z]/;
      var regexData = /(\d{2})\.(\d{2})\.(\d{4})+$/;
      var regexContratto = /(\d{4})\.(\d{4})+$/;
      var regexDomicilio = /([0-9]\s[a-z,A-Z])/;
      var regexAlfa = /([0-9,a-z,A-Z])/;
      var regexAnno = /(\d{4})+$/;
      var regexNumero = /(\d{1})+$/;
      var regexTelefono = /[+,0-9]$/;

      nome = $("#insertNome").val();
      nascita = $("#insertNascita").val();
      contratto = $("#insertContratto").val();
      statuto = $("#insertStatuto").val();
      indirizzo = $("#insertIndirizzo").val();
      domicilio = $("#insertDomicilio").val();
      telefono = $("#insertTelefono").val();
      professione = $("#insertProfessione").val();
      sede = $("#insertSede").val();
      rappresentante = $("#insertRappresentante").val();
      inizio = $("#insertInizio").val();
      fine = $("#insertFine").val();
      scolastico = $("#insertScolastico").val();

      // svuota campi messaggio
      $("#messaggioNome").empty();
      $("#messaggioNascita").empty();
      $("#messaggioContratto").empty();
      $("#messaggioStatuto").empty();
      $("#messaggioIndirizzo").empty();
      $("#messaggioDomicilio").empty();
      $("#messaggioTelefono").empty();
      $("#messaggioProfessione").empty();
      $("#messaggioSede").empty();
      $("#messaggioRappresentante").empty();
      $("#messaggioInizio").empty();
      $("#messaggioFine").empty();
      $("#messaggioScolastico").empty();

      if(regexTesto.test(nome)){
        $("#messaggioNome").append("il formato va bene");
      }
      else{
        $("#messaggioNome").append("formato errato: inserire solo lettere");
        n++;
      }

      if(regexData.test(nascita)){
        $("#messaggioNascita").append("il formato va bene");
      }
      else{
        $("#messaggioNascita").append("formato errato: dd.mm.yyyy");
        n++;
      }

      if(regexContratto.test(contratto)){
        $("#messaggioContratto").append("il formato va bene");
      }
      else{
        $("#messaggioContratto").append("formato errato: nnnn.nnnn");
        n++;
      }

      if(regexTesto.test(statuto)){
        $("#messaggioStatuto").append("il formato va bene");
      }
      else{
        $("#messaggioStatuto").append("inserire solo lettere");
        n++;
      }

      if(regexAlfa.test(indirizzo)){
        $("#messaggioIndirizzo").append("il formato va bene");
      }
      else{
        $("#messaggioIndirizzo").append("inserire lettere o numeri");
        n++;
      }

      if(regexDomicilio.test(domicilio)){
        $("#messaggioDomicilio").append("il formato va bene");
      }
      else{
        $("#messaggioDomicilio").append("formato errato: CAP luogo");
        n++
      }

      if(regexTelefono.test(telefono)){
        $("#messaggioTelefono").append("il formato va bene");
      }
      else{
        $("#messaggioTelefono").append("inserire un numero di telefono valido");
        n++
      }

      if(regexTesto.test(professione)){
        $("#messaggioProfessione").append("il formato va bene");
      }
      else{
        $("#messaggioProfessione").append("inserire solo lettere");
        n++;
      }

      if(regexTesto.test(sede)){
        $("#messaggioSede").append("il formato va bene");
      }
      else{
        $("#messaggioSede").append("inserire un nome, solo lettere");
        n++;
      }

      if(regexTesto.test(rappresentante)){
        $("#messaggioRappresentante").append("il formato va bene");
      }
      else{
        $("#messaggioRappresentante").append("inserire solo lettere");
        n++;
      }

      if(regexData.test(inizio)){
        $("#messaggioInizio").append("il formato va bene");
      }
      else{
        $("#messaggioInizio").append("formato errato: dd.mm.yyyy");
        n++;
      }

      if(regexAnno.test(fine)){
        $("#messaggioFine").append("il formato va bene");
      }
      else{
        $("#messaggioFine").append("formato errato: yyyy");
        n++;
      }

      if(regexNumero.test(scolastico)){
        $("#messaggioScolastico").append("il formato va bene");
      }
      else{
        $("#messaggioScolastico").append("formato errato: inserisci un numero");
        n++;
      }
      if($("#formatoreSel").val()!="" && $("#datoreSel").val()>0 && $("#gruppoSel").val()>0){
      }
      else{
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
      <h1 class="col-xs-12">Modifica Apprendista</h1>
      <br>
      <form id="formInsert" method="post">
        <div id="nomeDiv" class="col-xs-6">
          <label id="nomeLb">Nome e cognome:</label>
          <input type="text" name="insertNome" id="insertNome" value="<?php echo $row["nome"] ?>" class="form-control" placeholder="nome cognome" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioNome"></div>
        </div>
        <div id="nascitaDiv" class="col-xs-6">
          <label id="nascitaLb">Data di nascita:</label>
          <input type="text" name="insertNascita" id="insertNascita" value="<?php echo $row["nascita"] ?>" cclass="form-control" placeholder="dd.mm.yyyy" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioNascita"></div>
        </div>
        <div id="contrattoDiv" class="col-xs-6">
          <label id="contrattoLb">Numero di contratto:</label>
          <input type="text" name="insertContratto" id="insertContratto" value="<?php echo $row["contratto"] ?>" c class="form-control" placeholder="nnnn.nnnn" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioContratto"></div>
        </div>
        <div id="statutoDiv" class="col-xs-6">
          <label id="statutoLb">Statuto contratto:</label>
          <input type="text" name="insertStatuto" id="insertStatuto" value="<?php echo $row["gruppoSel"] ?>" c class="form-control" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioStatuto"></div>
        </div>
        <div id="indirizzoDiv" class="col-xs-6">
          <label id="indirizzoLb">Indirizzo:</label>
          <input type="text" name="insertIndirizzo" id="insertIndirizzo" class="form-control" placeholder="es: viale Filippo 15" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioIndirizzo"></div>
        </div>
        <div id="domicilioDiv" class="col-xs-6">
          <label id="domicilioLb">Domicilio:</label>
          <input type="text" name="insertDomicilio" id="insertDomicilio" class="form-control" placeholder="nnnn luogo" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioDomicilio"></div>
        </div>
        <div id="telefonoDiv" class="col-xs-6">
          <label id="telefonoLb">Telefono:</label>
          <input type="text" name="insertTelefono" id="insertTelefono" class="form-control" placeholder="es: 012 345 67 89" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioTelefono"></div>
        </div>
        <div id="professioneDiv" class="col-xs-6">
          <label id="professioneLb">Professione:</label>
          <input type="text" name="insertProfessione" id="insertProfessione" class="form-control" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioProfessione"></div>
        </div>
        <div id="sedeDiv" class="col-xs-6">
          <label id="sedeLb">Sede:</label>
          <input type="text" name="insertSede" id="insertSede" class="form-control" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioSede"></div>
        </div>
        <div id="rappresentanteDiv" class="col-xs-6">
          <label id="rappresentanteLb">Rappresentante:</label>
          <input type="text" name="insertRappresentante" id="insertRappresentante" class="form-control" placeholder="nome cognome" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioRappresentante"></div>
        </div>
        <div id="inizioDiv" class="col-xs-4">
          <label id="inizioLb">Inizio contratto:</label>
          <input type="text" name="insertInizio" id="insertInizio" class="form-control" placeholder="dd.mm.yyyy" required="required"/>
          <div class="col-xs-12  messaggio" id="messaggioInizio"></div>
        </div>
        <div id="fineDiv" class="col-xs-4">
          <label id="fineLb">Anno fine contratto:</label>
          <input type="text" name="insertFine" id="insertFine" class="form-control" placeholder="yyyy" required="required"/>
          <div class="col-xs-12  messaggio" id="messaggioFine"></div>
        </div>
        <div id="scolasticoDiv" class="col-xs-4">
          <label id="scolasticoLb">Anno scolastico:</label>
          <input type="text" name="insertScolastico" id="insertScolastico" class="form-control" placeholder="n"required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioScolastico"> </div>
        </div>
        <div class="col-xs-4" >
          <label id="datoreLb" >Datore:</label>
          <select name="datoreSel" id="datoreSel" class="form-control">
          <option selected="true" value="0">-- seleziona --</option>
          <?php
          try{
            $gruppo = $conn->prepare("SELECT dat_nome 'nome', dat_id AS 'id' from datore");
            $gruppo->execute();
          }
          catch(PDOException $e)
          {
            echo $e;
          }
            while($row = $gruppo->fetch(PDO::FETCH_ASSOC)){
          ?>
            <option value="<?php echo $row["id"] ?>"><?php echo $row["nome"] ?></option>
          <?php
            }
          ?>
        </select>
        </div>
        <div class="col-xs-4">
          <label id="formatoreLb">Formatore:</label>
          <select name="formatoreSel" id="formatoreSel" class="form-control">
          <option selected="true" value="0">-- seleziona --</option>
        </select>
        </div>
        <div class="col-xs-4">
          <label id="inserimentoLb">Anno inserimento:</label>
          <select  id="gruppoSel" name="gruppoSel" class="form-control">
              <option value="0">-- seleziona --</option>
            <?php
            try{
              $gruppo = $conn->prepare("SELECT grui_id AS 'id', grui_nome AS 'nome' from gruppoInserimento");
              $gruppo->execute();
            }
            catch(PDOException $e)
            {
              echo $e;
            }
              while($row = $gruppo->fetch(PDO::FETCH_ASSOC)){
            ?>
              <option value="<?php echo $row["id"] ?>"><?php echo $row["nome"] ?></option>
            <?php
              }
            ?>
          </select>
        </div>
      </form>
    </div>
  </body>
  </html>
  <?php
}
else{
      echo "<script>location.href='apprendisti.php'</script>";
}
?>
