<!-- pagina per la modifica degli apprendisti-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null) && ($_SESSION['tipo']=="master" OR $_SESSION['tipo']=="admin") && isset($_POST["modifica"])){ // da riguardare
  $modifica = $_POST["modifica"];
  // idContratto/annoScolastico/annoFine
  $m = explode("/",$modifica);
  $contratto = $m[0];
  $scolastico = $m[1];
  $fine = $m[2];
  try{
    $query = $conn->prepare("SELECT app.app_idContratto AS 'contratto', app.app_nome AS 'nome', app.app_telefono AS 'telefono', app.app_dataNascita AS 'nascita',
      app.app_rappresentante AS 'rappresentante', app.app_statuto AS 'statuto', app.app_indirizzo AS 'indirizzo', app.app_domicilio AS 'domicilio',app.app_osservazioni AS 'osservazioni',
      app.app_professione AS 'professione',  app.app_annoScolastico AS 'annoScolastico', app.app_annoFine AS 'annoFine', app.app_dataInizio AS 'dataInizio',
      grui_id AS 'gruppo', sed.sed_nome AS 'sede', dat.dat_nome AS 'datore',dat.dat_id AS 'idDatore', app.for_email AS 'formatore' FROM apprendista app
      JOIN datore dat ON dat.dat_id = app.dat_id
      JOIN sede sed ON sed.sed_id = app.sed_id
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
    <title>Modifica Apprendisti</title>
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
            //alert(result);
            $("#formatoreSel").find("option").remove();
            for (var i = 0; i < result.length; i++) {
              risultato = result[i].split("/");
              $("#formatoreSel").append("<option value='"+risultato[1]+"'>"+risultato[0]+"</option>")
            }
        }});
    });

    $("#bSalva").click(function(){
      var n = 0;
      // regex e creazioni variabili
      var regexTesto = /^([a-z A-Z])+$/;
      var regexData = /^(\d{2})\.(\d{2})\.(\d{4})+$/;
      var regexContratto = /^(\d{4})\.(\d{4})+$/;
      var regexDomicilio = /^(\d{4})\s([a-z ,A-Z])+$/;
      var regexAlfa = /^([0-9 \- () a-z,A-Z])+$/;
      var regexAnno = /^(\d{4})+$/;
      var regexNumero = /^(\d{1})+$/;
      var regexTelefono = /^[+]?[\s 0-9 \s]+$/;

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

      // svuota campi messaggio + correggi bordi
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
      $("#messaggioDatore").empty();
      $("#messaggioFormatore").empty();
      $("#messaggioAnno").empty();
      $("#insertNome").css("border","1px solid #ccc");
      $("#insertNascita").css("border","1px solid #ccc");
      $("#insertContratto").css("border","1px solid #ccc");
      $("#insertStatuto").css("border","1px solid #ccc");
      $("#insertIndirizzo").css("border","1px solid #ccc");
      $("#insertDomicilio").css("border","1px solid #ccc");
      $("#insertTelefono").css("border","1px solid #ccc");
      $("#insertProfessione").css("border","1px solid #ccc");
      $("#insertSede").css("border","1px solid #ccc");
      $("#insertRappresentante").css("border","1px solid #ccc");
      $("#insertInizio").css("border","1px solid #ccc");
      $("#insertFine").css("border","1px solid #ccc");
      $("#insertScolastico").css("border","1px solid #ccc");
      $("#formatoreSel").css("border","1px solid #ccc");
      $("#gruppoSel").css("border","1px solid #ccc");
      $("#datoreSel").css("border","1px solid #ccc");

      if(regexTesto.test(nome)){
        $("#messaggioNome").append("il formato va bene");
      }
      else{
        $("#messaggioNome").append("formato errato: inserire solo lettere");
        $("#insertNome").css("border","1px solid red");
        n++;
      }

      if(regexData.test(nascita)){
        $("#messaggioNascita").append("il formato va bene");
      }
      else{
        $("#messaggioNascita").append("formato errato: dd.mm.yyyy");
        $("#insertNascita").css("border","1px solid red");
        n++;
      }

      if(regexContratto.test(contratto)){
        $("#messaggioContratto").append("il formato va bene");
      }
      else{
        $("#messaggioContratto").append("formato errato: nnnn.nnnn");
        $("#insertContratto").css("border","1px solid red");
        n++;
      }

      if(regexTesto.test(statuto)){
        $("#messaggioStatuto").append("il formato va bene");
      }
      else{
        $("#messaggioStatuto").append("inserire solo lettere");
        $("#insertStatuto").css("border","1px solid red");
        n++;
      }

      if(regexAlfa.test(indirizzo)){
        $("#messaggioIndirizzo").append("il formato va bene");
      }
      else{
        $("#messaggioIndirizzo").append("inserire lettere o numeri");
        $("#insertIndirizzo").css("border","1px solid red");
        n++;
      }

      if(regexDomicilio.test(domicilio)){
        $("#messaggioDomicilio").append("il formato va bene");
      }
      else{
        $("#messaggioDomicilio").append("formato errato: CAP luogo");
        $("#insertDomicilio").css("border","1px solid red");
        n++
      }

      if(regexTelefono.test(telefono)){
        $("#messaggioTelefono").append("il formato va bene");
      }
      else{
        $("#messaggioTelefono").append("inserire un numero di telefono valido");
        $("#insertTelefono").css("border","1px solid red");
        n++
      }

      if(regexAlfa.test(professione)){
        $("#messaggioProfessione").append("il formato va bene");
      }
      else{
        $("#messaggioProfessione").append("inserire lettere o numeri");
        $("#insertProfessione").css("border","1px solid red");
        n++;
      }

      if(regexTesto.test(sede)){
        $("#messaggioSede").append("il formato va bene");
      }
      else{
        $("#messaggioSede").append("inserire un nome, solo lettere");
        $("#insertSede").css("border","1px solid red");
        n++;
      }

      if(regexTesto.test(rappresentante)){
        $("#messaggioRappresentante").append("il formato va bene");
      }
      else{
        $("#messaggioRappresentante").append("inserire solo lettere");
        $("#insertRappresentante").css("border","1px solid red");
        n++;
      }

      if(regexData.test(inizio)){
        $("#messaggioInizio").append("il formato va bene");
      }
      else{
        $("#messaggioInizio").append("formato errato: dd.mm.yyyy");
        $("#insertInizio").css("border","1px solid red");
        n++;
      }

      if(regexAnno.test(fine)){
        $("#messaggioFine").append("il formato va bene");
      }
      else{
        $("#messaggioFine").append("formato errato: yyyy");
        $("#insertFine").css("border","1px solid red");
        n++;
      }

      if(regexNumero.test(scolastico)){
        $("#messaggioScolastico").append("il formato va bene");
      }
      else{
        $("#messaggioScolastico").append("formato errato: inserisci un numero");
        $("#insertScolastico").css("border","1px solid red");
        n++;
      }
      if($("#formatoreSel").val()!=""){
        $("#messaggioFormatore").append("la scelta va bene");
      }
      else{
        $("#messaggioFormatore").append("la scelta non va bene");
        $("#formatoreSel").css("border","1px solid red");
        n++;
      }

      if($("#datoreSel").val()>0){
        $("#messaggioDatore").append("la scelta va bene");
      }
      else {
        $("#messaggioDatore").append("la scelta non va bene");
        $("#datoreSel").css("border","1px solid red");
        n++;
      }
      if($("#gruppoSel").val()>0){
        $("#messaggioAnno").append("la scelta va bene");
      }
      else {
        $("#messaggioAnno").append("la scelta non va bene");
        $("#gruppoSel").css("border","1px solid red");
        n++;
      }
      // se non ci sono errori submitta
      if(n==0){
        //alert("manda");
        $("#formModifica").submit();
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
      <form id="formModifica" method="post">
        <div id="nomeDiv" class="col-sm-4 col-xs-6">
          <label id="nomeLb">Nome e cognome:</label>
          <input type="text" name="insertNome" id="insertNome" value="<?php echo $row["nome"] ?>" class="form-control" placeholder="nome cognome" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioNome"></div>
        </div>
        <div id="nascitaDiv" class="col-sm-4 col-xs-6">
          <label id="nascitaLb">Data di nascita:</label>
          <input type="text" name="insertNascita" id="insertNascita" value="<?php
          $dato = explode('-', $row["nascita"]);
          echo $dato[2].'.'.$dato[1].'.'.$dato[0];
           ?>" class="form-control" placeholder="dd.mm.yyyy" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioNascita"></div>
        </div>
        <div id="contrattoDiv" class="col-sm-4 col-xs-6">
          <label id="contrattoLb">Numero di contratto:</label>
          <input type="text" name="insertContratto" readonly="true" id="insertContratto" value="<?php echo $row["contratto"] ?>" class="form-control" placeholder="nnnn.nnnn" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioContratto"></div>
        </div>
        <div id="statutoDiv" class="col-sm-4 col-xs-6">
          <label id="statutoLb">Statuto contratto:</label>
          <input type="text" name="insertStatuto" id="insertStatuto" value="<?php echo $row["statuto"] ?>" class="form-control" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioStatuto"></div>
        </div>
        <div id="indirizzoDiv" class="col-sm-4 col-xs-6">
          <label id="indirizzoLb">Indirizzo:</label>
          <input type="text" name="insertIndirizzo" id="insertIndirizzo" value="<?php echo $row["indirizzo"] ?>"class="form-control" placeholder="es: viale Filippo 15" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioIndirizzo"></div>
        </div>
        <div id="domicilioDiv" class="col-sm-4 col-xs-6">
          <label id="domicilioLb">Domicilio:</label>
          <input type="text" name="insertDomicilio" id="insertDomicilio" value="<?php echo $row["domicilio"] ?>" class="form-control" placeholder="nnnn luogo" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioDomicilio"></div>
        </div>
        <div id="telefonoDiv" class="col-sm-4 col-xs-6">
          <label id="telefonoLb">Telefono:</label>
          <input type="text" name="insertTelefono" id="insertTelefono" value="<?php echo $row["telefono"] ?>" class="form-control" placeholder="es: 012 345 67 89" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioTelefono"></div>
        </div>
        <div id="professioneDiv" class="col-sm-4 col-xs-6">
          <label id="professioneLb">Professione:</label>
          <input type="text" name="insertProfessione" id="insertProfessione" value="<?php echo $row["professione"] ?>" class="form-control" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioProfessione"></div>
        </div>
        <div id="sedeDiv" class="col-sm-4 col-xs-6">
          <label id="sedeLb">Sede:</label>
          <input type="text" name="insertSede" id="insertSede" value="<?php echo $row["sede"] ?>" class="form-control" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioSede"></div>
        </div>
        <div id="rappresentanteDiv" class="col-sm-4 col-xs-6">
          <label id="rappresentanteLb">Rappresentante:</label>
          <input type="text" name="insertRappresentante" id="insertRappresentante" value="<?php echo $row["rappresentante"] ?>" class="form-control" placeholder="nome cognome" required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioRappresentante"></div>
        </div>
        <div id="inizioDiv" class="col-sm-4 col-xs-6">
          <label id="inizioLb">Inizio contratto:</label>
          <input type="text" name="insertInizio" id="insertInizio" class="form-control" value="<?php
          $dato = explode('-', $row["dataInizio"]);
          echo $dato[2].'.'.$dato[1].'.'.$dato[0];?>" placeholder="dd.mm.yyyy" required="required"/>
          <div class="col-xs-12  messaggio" id="messaggioInizio"></div>
        </div>
        <div id="fineDiv" class="col-sm-4 col-xs-6">
          <label id="fineLb">Anno fine contratto:</label>
          <input type="text" name="insertFine" readonly="true" id="insertFine" class="form-control" value="<?php echo $row["annoFine"] ?>" placeholder="yyyy" required="required"/>
          <div class="col-xs-12  messaggio" id="messaggioFine"></div>
        </div>
        <div id="scolasticoDiv" class="col-sm-4 col-xs-6">
          <label id="scolasticoLb">Anno scolastico:</label>
          <input type="text" name="insertScolastico" readonly="true"  id="insertScolastico"value="<?php echo $row["annoScolastico"] ?>"  class="form-control" placeholder="n"required="required"/>
          <div class="col-xs-12 messaggio" id="messaggioScolastico"> </div>
        </div>
        <div class="col-sm-4 col-xs-6" >
          <label id="datoreLb" >Datore:</label>
            <select name="datoreSel" id="datoreSel" class="form-control">
            <?php
            try{
              $gruppo = $conn->prepare("SELECT dat_nome 'nome', dat_id AS 'id' from datore where dat_flag=1");
              $gruppo->execute();
            }
            catch(PDOException $e)
            {
              echo $e;
            }
              while($r = $gruppo->fetch(PDO::FETCH_ASSOC)){
                if($row["idDatore"]==$r["id"]){
            ?>
              <option selected="true" value="<?php echo $r["id"] ?>"><?php echo $r["nome"] ?></option>
              <?php
                }
                else{
                  ?>
                  <option value="<?php echo $r["id"] ?>"><?php echo $r["nome"] ?></option>
                  <?php
                }
              ?>
            <?php
              }
            ?>
          </select>
          <div class="col-xs-12 messaggio" id="messaggioDatore"></div>
        </div>
        <div class="col-sm-4 col-xs-6">
          <label id="formatoreLb">Formatore:</label>
          <select name="formatoreSel" id="formatoreSel" class="form-control">
            <?php
            try{
              $formatore = $conn->prepare("SELECT for_nome AS 'nome',for_email AS 'email' from formatore where for_flag=1 AND dat_id=:id");
              $formatore->bindParam(":id",$row['idDatore']);
              $formatore->execute();
            }
            catch(PDOException $e)
            {
              echo $e;
            }
              while($r=$formatore->fetch(PDO::FETCH_ASSOC)){
                if($r["email"]==$row["formatore"]){
            ?>
              <option selected="true" value="<?php echo $r["email"] ?>"><?php echo $r["nome"] ?></option>
            <?php
              }else{
            ?>
              <option value="<?php echo $r["email"] ?>"><?php echo $r["nome"] ?></option>
            <?php
              }
              }
            ?>
        </select>
        <div class="col-xs-12 messaggio" id="messaggioFormatore"></div>
        </div>
        <div class="col-sm-4 col-xs-6">
          <label id="inserimentoLb">Anno inserimento:</label>
          <select  id="gruppoSel" name="gruppoSel" class="form-control">
            <?php
            try{
              $gruppo = $conn->prepare("SELECT grui_id AS 'id', grui_nome AS 'nome' from gruppoInserimento");
              $gruppo->execute();
            }
            catch(PDOException $e)
            {
              echo $e;
            }
              while($r=$gruppo->fetch(PDO::FETCH_ASSOC)){
                if($r["id"]==$row["gruppo"]){
            ?>
              <option selected="true" value="<?php echo $r["id"]?>"><?php echo $r["nome"] ?></option>
            <?php
              }else{
            ?>
              <option value="<?php echo $r["id"] ?>"><?php echo $r["nome"] ?></option>
            <?php
              }
            }
            ?>
          </select>
          <div class="col-xs-12 messaggio" id="messaggioAnno"></div>
        </div>
        <div class="col-sm-4  col-xs-6">
          <label id="osservazioniLb">Osservazioni:</label>
          <textarea class="col-xs-12 form-control" name="osservazioni" style="margin-top:0px" ><?php echo $row["osservazioni"]?>
          </textarea>
          <div class="col-xs-12 messaggio" id=""> </div>
        </div>
        <input type="hidden" name="modifica" value="<?php echo $row['contratto']."/".$row["annoScolastico"]."/".$row["annoFine"];?>"/>
        <input type="hidden" name="dati" value="dati"/>
      </form>
      <div>
        <label id="">Salvataggio: </label>
        <button type="button" id="bSalva" class="btn btn-lg btn-default col-sm-4 col-xs-6">Salva</button>
      </div>
      <div class="col-xs-6" id="errori">
      </div>
    </div>
  </body>
  </html>
  <?php
}
else{
    echo "<script>location.href='apprendisti.php'</script>";
}
?>
