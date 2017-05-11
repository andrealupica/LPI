<!-- pagina per la gestione degli apprendisti-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null)){ // da riguardare
  try{
    $query = $conn->prepare("SELECT app.app_idContratto AS 'contratto', app.app_nome AS 'nome', app.app_telefono AS 'telefono', app.app_dataNascita AS 'nascita',
      app.app_rappresentante AS 'rappresentante', app.app_statuto AS 'statuto', app.app_indirizzo AS 'indirizzo', app.app_domicilio AS 'domicilio',
      app.app_professione AS 'professione',  app.app_annoScolastico AS 'annoScolastico', app.app_annoFine AS 'annoFine', app.app_dataInizio AS 'dataInizio',
      grui_id AS 'gruppo', sed.sed_nome AS 'sede', dat.dat_nome AS 'datore', form.for_nome AS 'formatore' FROM apprendista app
      JOIN datore dat ON dat.dat_id = app.dat_id
      JOIN formatore form ON form.for_email = app.for_email
      JOIN sede sed ON sed.sed_id = app.sed_id
      WHERE app.app_flag=1;");
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
    <title>Apprendisti</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/script.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link href="css/stylebase.css" rel="stylesheet">
  </head>
  <style>
  .col-xs-1,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9,.col-xs-10,.col-xs-11,.col-xs-12,
  .col-sm-1,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-sm-10,.col-sm-11,.col-sm-12,
  .col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12,
  .col-lg-1,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-lg-10,.col-lg-11,.col-lg-12,
  .col-xl-1,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9,.col-xl-10,.col-xl-11,.col-xl-12{
    margin-top:5px;
  }
  .messaggio{
    margin-top:5px;
  }
  </style>
  <script>
  $(document).ready(function(){
    // funzione per la visibilità delle colonne quando i checkbox vengono cliccati
    $("label input").change(function(){
      var valore=this.value;
      var checkbox = $(this);
      // per ogni tr nella tabella
        $("#table").find("tr").each(function(index) {
          // controlla se il checkbox è checkbox
          if($(checkbox).is(':checked')){
            $(this).find(".tb"+valore+"").show();
          }
          else{
            $(this).find(".tb"+valore+"").hide();
          }
        });
    });
    // quando il gruppo cambia
    $("#selectgruppo").change(function(){
      var valore = $(this).val();
      $("#table").find("tr").each(function(index) {
        if(valore==0){
          $(this).show();
        }
        else{
          if($(this).attr('id')!="th"){
            if($(this).attr('id')!=valore){
              $(this).hide();
            }
            else{
              $(this).show();
            }
          }
        }
      })
    });
    // quando il bottone per i checkbox viene cliccato
    $("#bCheckbox").click(function(){
      $("#checkbox").toggle();
      if($("#bCheckbox").text()=="Nascondi Checkbox"){
        $("#bCheckbox").text("Visualizza Checkbox");
      }
      else{
        $("#bCheckbox").text("Nascondi Checkbox");
      }
    });

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
  function modalInsert(){
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
  }

  </script>
  <body class="body" style="min-width:680px;">
    <?php include_once "menu.php";
    ?>
    <div class="container">
      <h1 class="col-xs-12">Apprendisti</h1>
      <br>
      <div class="col-xs-12">
        <label class="col-sm-3 col-xs-4 control-label">Ricerca: </label>
        <div class="col-sm-5 col-xs-8">
          <div class="input-group">
            <span class="input-group-addon glyphicon glyphicon-search"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
            <input type="text" class="form-control" id="search"></input>
          </div>
        </div>
        <?php             if($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master"){ ?>
        <div class="col-sm-2 col-xs-6">
          <button class="btn btn-primary col-xs-12" data-toggle="modal" data-target="#myModalI" onclick="modalInsert()">
            <span class="glyphicon glyphicon-pencil"></span> inserisci
          </button>
        </div>
        <div class="col-sm-2 col-xs-6">
          <form role="form" action="" method="post" name="form1" enctype="multipart/form-data">
             <fieldset>
                 <input name="idCSV" type="file" id="idCSV" accept=".csv" />
                 <input type="submit" name="Import" value="Importa " class="btn btn-primary" />
             </fieldset>
          </form>
        </div>
        <?php } ?>
      </div>
      <div class="col-xs-12">
        <button class="btn btn-default btn-md" id="bCheckbox">visualizza Checkbox</button>
      </div>
      <div class="col-xs-12" id="checkbox">
        <label class="col-xs-4">n Contratto: <input type="checkbox" name="contratto" value="contratto" checked="true" id="checkcontratto"></label>
        <label class="col-xs-4">nome apprendista: <input type="checkbox" name="nome" value="nome" checked="true" id="checknome"></label>
        <label class="col-xs-4">data di nascita: <input type="checkbox" name="dataNascita" value="dataNascita" id="checkdataNascita"></label>
        <label class="col-xs-4">telefono: <input type="checkbox" name="telefono" value="telefono" id="checktelefono"></label>
        <label class="col-xs-4">indirizzo: <input type="checkbox" name="indirizzo" value="indirizzo"  id="checkindirizzo"></label>
        <label class="col-xs-4">domicilio: <input type="checkbox" name="domicilio" value="domicilio"  id="checkdomicilio"></label>
        <label class="col-xs-4">statuto: <input  type="checkbox" name="statuto" value="statuto" id="checkstatuto"></label>
        <label class="col-xs-4">rappresentante: <input  type="checkbox" name="rappresentante" value="rappresentante" id="checkrappresentante"></label>
        <label class="col-xs-4">professione: <input  type="checkbox" name="professione" value="professione"  id="checkprofessione"></label>
        <label class="col-xs-4">sede: <input type="checkbox" name="sede" value="sede"  id="checksede"></label>
        <label class="col-xs-4">data di inizio: <input  type="checkbox" name="dataInizio" value="dataInizio" id="checkdataInizio"></label>
        <label class="col-xs-4">data di fine: <input  type="checkbox" name="dataFine" value="dataFine"  id="dataFine"></label>
        <label class="col-xs-4">anno scolastico: <input  type="checkbox" name="annoScolastico" value="annoScolastico"  id="checkannoScolastico"></label>
        <label class="col-xs-4">datore: <input  type="checkbox" name="datore" value="datore" checked="true" id="checkdatore"></label>
        <label class="col-xs-4">formatore: <input  type="checkbox" name="formatore" value="formatore" checked="true" id="formatore"></label>
      </div>
      <div class="col-xs-4">
        <span>
          <select name="gruppo" id="selectgruppo" class="form-control" style="margin-bottom:10px">
              <option value="0">mostra tutti</option>
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
        </span>
      </div>
      <div class="col-xs-6" id="errori">
      </div>
      <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="table" >
        <thead>
          <tr id="th">
            <th class="tbcontratto">Contratto</th>
            <th class="tbnome">Nome</th>
            <th class="tbdataNascita" style="display: none;">Nascita</th>
            <th class="tbtelefono" style="display: none;">Telefono</th>
            <th class="tbindirizzo" style="display: none;">Indirizzo</th>
            <th class="tbdomicilio" style="display: none;">Domicilio</th>
            <th class="tbstatuto" style="display: none;">Statuto</th>
            <th class="tbrappresentante" style="display: none;">Rappresentante</th>
            <th class="tbprofessione" style="display: none;">Professione</th>
            <th class="tbsede" style="display: none;">Sede</th>
            <th class="tbdataInizio" style="display: none;">Data inizio</th>
            <th class="tbdataFine" style="display: none;">Data fine</th>
            <th class="tbannoScolastico" style="display: none;">Anno scolastico</th>
            <th class="tbdatore">Datore</th>
            <th class="tbformatore">Formatore</th>
            <th class="">Dettaglio</th><?php
            if($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master"){ ?>
            <th class="">Modifica</th>
            <th class="">Elimina</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
        <?php
          while($row = $query->fetch(PDO::FETCH_ASSOC)){
          ?>
          <tr id="<?php echo $row["gruppo"]?>">
            <td class="tbcontratto"><?php echo $row["contratto"] ?></td>
            <td class="tbnome"><?php echo $row["nome"] ?></td>
            <td class="tbdataNascita"style="display: none;" ><?php
            $dato = explode('-', $row["nascita"]);
            echo $dato[2].'.'.$dato[1].'.'.$dato[0];
             ?></td>
            <td class="tbtelefono" style="display: none;"><?php echo $row["telefono"] ?></td>
            <td class="tbindirizzo" style="display: none;"><?php echo $row["indirizzo"] ?></td>
            <td class="tbdomicilio" style="display: none;"><?php echo $row["domicilio"] ?></td>
            <td class="tbstatuto" style="display: none;"><?php echo $row["statuto"]?></td>
            <td class="tbrappresentante" style="display: none;"><?php echo $row["rappresentante"] ?></td>
            <td class="tbprofessione" style="display: none;"><?php echo $row["professione"] ?></td>
            <td class="tbsede" style="display: none;"><?php echo $row["sede"] ?></td>
            <td class="tbdataInizio" style="display: none;"><?php
            $dato = explode('-', $row["dataInizio"]);
            echo $dato[2].'.'.$dato[1].'.'.$dato[0];?></td>
            <td class="tbdataFine" style="display: none;"><?php echo $row["annoFine"] ?></td>
            <td class="tbannoScolastico" style="display: none;"><?php echo $row["annoScolastico"] ?></td>
            <td class="tbdatore"><?php echo $row["datore"] ?></td>
            <td class="tbformatore"><?php echo $row["formatore"] ?></td>
            <td>
              <form method="post" action="apprendistiDettaglio.php">
                <button type="submit" name='buttonD' id="<?php echo $row['contratto']?>" class="btn btn-primary btn-md">
                   <span class="glyphicon glyphicon-option-horizontal"></span>
                </button>
                <input type="hidden" name="dettaglio" value="<?php echo $row['contratto']."/".$row["annoScolastico"]."/".$row["annoFine"];?>';" />
              </form>
            </td>
            <?php if($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master"){ ?>
            <td>
              <form method="post" action="apprendistiModifica.php">
                <button type="submit" name='buttonM' id="<?php echo $row['contratto']?>" class="btn btn-info btn-md">
                   <span class="glyphicon glyphicon-edit"></span>
                </button>
                <input type="hidden" name="modifica" value="<?php echo $row['contratto']."/".$row["annoScolastico"]."/".$row["annoFine"];?>';" />
              </form>
            </td>
            <td>
              <button type="button" name='buttonD' id="<?php echo $row['contratto']?>" class="btn btn-danger btn-md"
                 data-toggle="modal" data-target="#myModalD" onclick="document.getElementById('pElimina').value='<?php echo $row['contratto']."/".$row["annoScolastico"]."/".$row["annoFine"];?>';">
                 <span class="glyphicon glyphicon-remove"></span>
              </button>
            </td>
            <?php } ?>
          </tr>
          <?php } ?>
        </tbody>
        </table>

        <!-- modal -->
        <div class="container">
          <!-- Modal di eliminazione-->
          <div class="modal fade" id="myModalD" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Eliminazione apprendista</h4>
                </div>
                <div class="modal-body">
                  <div class="alert alert-danger">
                    Sei sicuro di voler eliminare l apprendista?
                  </div>
                </div>
                <div class="modal-footer">
                  <form method="post" action="">
                    <button type="submit" class="btn btn-default">ok</button>
                    <input type="hidden" name="apprendistaCancellato" id="pElimina" required="required"/>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="container">
          <!-- Modal di inserimento-->
          <div class="modal fade" id="myModalI" role="dialog">
            <div class="modal-dialog modal-dialog modal-lg">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 id="titoloInserimento" class="modal-title">Creazione di un account </h4>
                </div>
                <div class="modal-body">
                  <form id="formInsert" method="post">
                    <div class="row">
                      <div id="nomeDiv" class="col-xs-6">
                        <label id="nomeLb">Nome e cognome:</label>
                        <input type="text" name="insertNome" id="insertNome" class="form-control" placeholder="nome cognome" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioNome"></div>
                      </div>
                      <div id="nascitaDiv" class="col-xs-6">
                        <label id="nascitaLb">Data di nascita:</label>
                        <input type="text" name="insertNascita" id="insertNascita" class="form-control" placeholder="dd.mm.yyyy" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioNascita"></div>
                      </div>
                    </div>
                    <div class="row">
                      <div id="contrattoDiv" class="col-xs-6">
                        <label id="contrattoLb">Numero di contratto:</label>
                        <input type="text" name="insertContratto" id="insertContratto" class="form-control" placeholder="nnnn.nnnn" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioContratto"></div>
                      </div>
                      <div id="statutoDiv" class="col-xs-6">
                        <label id="statutoLb">Statuto contratto:</label>
                        <input type="text" name="insertStatuto" id="insertStatuto" class="form-control" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioStatuto"></div>
                      </div>
                    </div>
                    <div class="row">
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
                    </div>
                    <div class="row">
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
                    </div>
                    <div class="row">
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
                    </div>
                    <div class="row">
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
                    </div>
                    <div class="row">
                      <div class="col-xs-4" >
                        <label id="datoreLb" >Datore:</label>
                        <select name="datoreSel" id="datoreSel" class="form-control">
                        <option selected="true" value="0">-- seleziona --</option>
                        <?php
                        try{
                          $gruppo = $conn->prepare("SELECT dat_nome 'nome', dat_id AS 'id' from datore where dat_flag=1");
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
                    </span>
                    </div>
                    <input type="hidden" name="insert" value=""/>
                    <!-- <input type="hidden" name="insert" value="<?php echo $row['contratto']."/".$row["annoScolastico"]."/".$row["annoFine"];?>"/>-->
                  </form>
                </div>
                <div class="modal-footer">
                  <div class="col-xs-4">
                  </div>
                  <button type="submit" id="bInsert" class="btn btn-lg btn-default col-xs-4">Inserisci</button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <script>
    $("#search").keyup(function() {
      var value = this.value.toLowerCase();
      var words = value.split(' ');
      $("#table").find("tr").each(function(index) {
        if (index === 0) return;
        var ris = $(this).find("td").text().toLowerCase();
        var flag=0;
        // controllo se l'array di parole splittate è contenuto nella riga
        for (i = 0; i < words.length; i++) {
          if(ris.indexOf(words[i])!=-1){
          }
          else{
            flag=1;
          }
        }
        if(flag==0){
          $(this).show();
        }
        else{
          $(this).hide();
        }
        flag=0;
      });
    });
    </script>
  </body>
  </html>
  <?php
}
else{
  echo "non hai i permessi per visualizzare questa pagina";
}
?>
