<!-- pagina per la visione degli apprendisti-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null) && isset($_POST["dettaglio"])){
  $dettaglio = $_POST["dettaglio"];
  // idContratto/annoScolastico/annoFine
  $m = explode("/",$dettaglio);
  $contratto = $m[0];
  $scolastico = $m[1];
  $fine = $m[2];
  try{
    $query = $conn->prepare("SELECT app.app_idContratto AS 'contratto', app.app_nome AS 'nome', app.app_telefono AS 'telefono', app.app_dataNascita AS 'nascita',
      app.app_rappresentante AS 'rappresentante', app.app_statuto AS 'statuto', app.app_indirizzo AS 'indirizzo', app.app_domicilio AS 'domicilio',
      app.app_professione AS 'professione',  app.app_annoScolastico AS 'annoScolastico', app.app_annoFine AS 'annoFine', app.app_dataInizio AS 'dataInizio',app.app_osservazioni AS 'osservazioni',
      gru.grui_nome AS 'gruppo', sed.sed_nome AS 'sede', dat.dat_nome AS 'datore', form.for_nome AS 'formatore',form.for_email AS 'emailFormatore',
      form.for_osservazioni AS 'osservazioniFormatore', dat.dat_indirizzo AS 'datoreIndirizzo', dat.dat_domicilio AS 'datoreDomicilio',dat.dat_telefono AS 'datoreTelefono' FROM apprendista app
      JOIN datore dat ON dat.dat_id = app.dat_id
      JOIN formatore form ON form.for_email = app.for_email
      JOIN sede sed ON sed.sed_id = app.sed_id
      JOIN gruppoInserimento gru ON app.grui_id = gru.grui_id
      WHERE app.app_flag=1 && app.app_idContratto=:contratto && app.app_annoScolastico=:scolastico && app.app_annoFine=:fine;");
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
    <title>Dettaglio Apprendista</title>
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

  </script>
  <body class="body">
    <?php include_once "menu.php";
    $row = $query->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="container">
      <h1 class="col-xs-12">Dettaglio Apprendista</h1>
      <br>
      <form method="post" action="apprendistaPDF.php" target="_blank">
        <div id="nomeDiv" class="col-sm-4 col-xs-6">
          <label id="nomeLb">Nome e cognome:</label>
          <input type="text" name="insertNome" readonly="true" id="insertNome" value="<?php echo $row["nome"] ?>" class="form-control" placeholder="nome cognome" required="required"/>
        </div>
        <div id="nascitaDiv" class="col-sm-4 col-xs-6">
          <label id="nascitaLb">Data di nascita:</label>
          <input type="text" name="insertNascita" id="insertNascita" value="<?php
          $dato = explode('-', $row["nascita"]);
          echo $dato[2].'.'.$dato[1].'.'.$dato[0];
           ?>" class="form-control" readonly="true" placeholder="dd.mm.yyyy" required="required"/>
        </div>
        <div id="contrattoDiv" class="col-sm-4 col-xs-6">
          <label id="contrattoLb">Numero di contratto:</label>
          <input type="text" name="insertContratto" readonly="true" id="insertContratto" value="<?php echo $row["contratto"] ?>" class="form-control" placeholder="nnnn.nnnn" required="required"/>
        </div>
        <div id="statutoDiv" class="col-sm-4 col-xs-6">
          <label id="statutoLb">Statuto contratto:</label>
          <input type="text" name="insertStatuto" readonly="true" id="insertStatuto" value="<?php echo $row["statuto"] ?>" class="form-control" required="required"/>
        </div>
        <div id="indirizzoDiv" class="col-sm-4 col-xs-6">
          <label id="indirizzoLb">Indirizzo:</label>
          <input type="text" name="insertIndirizzo"readonly="true"  id="insertIndirizzo" value="<?php echo $row["indirizzo"] ?>"class="form-control" placeholder="es: viale Filippo 15" required="required"/>
        </div>
        <div id="domicilioDiv" class="col-sm-4 col-xs-6">
          <label id="domicilioLb">Domicilio:</label>
          <input type="text" name="insertDomicilio" readonly="true" id="insertDomicilio" value="<?php echo $row["domicilio"] ?>" class="form-control" placeholder="nnnn luogo" required="required"/>
        </div>
        <div id="telefonoDiv" class="col-sm-4 col-xs-6">
          <label id="telefonoLb">Telefono:</label>
          <input type="text" name="insertTelefono" readonly="true" id="insertTelefono" value="<?php echo $row["telefono"] ?>" class="form-control" placeholder="es: 012 345 67 89" required="required"/>
        </div>
        <div id="professioneDiv" class="col-sm-4 col-xs-6">
          <label id="professioneLb">Professione:</label>
          <input type="text" name="insertProfessione" readonly="true"  id="insertProfessione" value="<?php echo $row["professione"] ?>" class="form-control" required="required"/>
        </div>
        <div id="sedeDiv" class="col-sm-4 col-xs-6">
          <label id="sedeLb">Sede:</label>
          <input type="text" name="insertSede" readonly="true" id="insertSede" value="<?php echo $row["sede"] ?>" class="form-control" required="required"/>
        </div>
        <div id="rappresentanteDiv" class="col-sm-4 col-xs-6">
          <label id="rappresentanteLb">Rappresentante:</label>
          <input type="text" name="insertRappresentante" readonly="true"  id="insertRappresentante" value="<?php echo $row["rappresentante"] ?>" class="form-control" placeholder="nome cognome" required="required"/>
        </div>
        <div id="inizioDiv" class="col-sm-4 col-xs-6">
          <label id="inizioLb">Inizio contratto:</label>
          <input type="text" name="insertInizio" readonly="true" id="insertInizio" class="form-control" value="<?php
          $dato = explode('-', $row["dataInizio"]);
          echo $dato[2].'.'.$dato[1].'.'.$dato[0];?>" placeholder="dd.mm.yyyy" required="required"/>
        </div>
        <div id="fineDiv" class="col-sm-4 col-xs-6">
          <label id="fineLb">Anno fine contratto:</label>
          <input type="text" name="insertFine" readonly="true" id="insertFine" class="form-control" value="<?php echo $row["annoFine"] ?>" placeholder="yyyy" required="required"/>
        </div>
        <div id="scolasticoDiv" class="col-sm-4 col-xs-6">
          <label id="scolasticoLb">Anno scolastico:</label>
          <input type="text" name="insertScolastico" readonly="true"  id="insertScolastico"value="<?php echo $row["annoScolastico"] ?>"  class="form-control" placeholder="n" required="required"/>
        </div>
        <div class="col-sm-4 col-xs-6">
          <label id="inserimentoLb">Anno inserimento:</label>
          <input type="text" name="insertGruppo" readonly="true"  id="insertGruppo "value="<?php echo $row["gruppo"] ?>"  class="form-control" placeholder="" required="required"/>
        </div>
        <div class="col-sm-4 col-xs-12">
          <label id="osservazioniLb">Osservazioni:</label>
          <textarea class="col-xs-12 form-control" name="osservazioni" readonly="true" style="margin-top:0px" ><?php echo $row["osservazioni"]?>
          </textarea>
        </div>
        <div class="col-sm-4 col-xs-6" >
          <label id="scolasticoLb">Datore:</label>
          <input type="text" name="insertDatore" readonly="true"  id="insertDatore "value="<?php echo $row["datore"] ?>"  class="form-control" placeholder="" required="required"/>
        </div>
        <div class="col-sm-4 col-xs-6" >
          <label id="scolasticoLb">Indirizzo datore:</label>
          <input type="text" name="insertDatoreIndirizzo" readonly="true"  id="insertDatoreIndirizzo "value="<?php echo $row["datoreIndirizzo"] ?>"  class="form-control" placeholder="" required="required"/>
        </div>
        <div class="col-sm-4 col-xs-6" >
          <label id="scolasticoLb">domicilio datore:</label>
          <input type="text" name="insertDatoreDomicilio" readonly="true"  id="insertDatoreDomicilio "value="<?php echo $row["datoreDomicilio"] ?>"  class="form-control" placeholder="" required="required"/>
        </div>
        <div class="col-sm-4 col-xs-6" >
          <label id="scolasticoLb">Telefono datore:</label>
          <input type="text" name="insertDatoreTelefono" readonly="true"  id="insertDatoreTelefono" value="<?php echo $row["datoreTelefono"] ?>"  class="form-control" placeholder="" required="required"/>
        </div>
        <div class="col-sm-4 col-xs-6">
          <label id="formatoreLb">Formatore:</label>
          <input type="text" name="insertFormatore" readonly="true"  id="insertFormatore "value="<?php echo $row["formatore"] ?>"  class="form-control" placeholder="" required="required"/>
        </div>
        <div class="col-sm-4 col-xs-6">
          <label id="formatoreLb">Email formatore:</label>
          <input type="text" name="insertEmailFormatore" readonly="true"  id="insertEmailFormatore "value="<?php echo $row["emailFormatore"] ?>"  class="form-control" placeholder="" required="required"/>
        </div>
        <div class="col-sm-4 col-xs-12" style="margin-bottom:10px">
          <label id="osservazionifLb">Osservazioni Formatore:</label>
          <textarea class="col-xs-12 form-control" readonly="true" name="osservazioniFormatore" style="margin-top:0px" ><?php echo $row["osservazioniFormatore"]?>
          </textarea>
        </div>
        <div class="col-sm-3 col-xs-6" style="margin-top:0px">
          <button type="submit" class="btn btn-primary col-xs-12">
            PDF
          </button>
        </div>
        <input type="hidden" name="pdf"/>
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
