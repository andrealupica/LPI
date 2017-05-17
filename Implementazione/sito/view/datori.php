<!-- pagina per la gestione dei formatori-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null)){ // da riguardare
  try{
    $query = $conn->prepare("SELECT dat_id AS 'id', dat_nome AS 'nome', dat_indirizzo AS 'indirizzo', dat_domicilio AS 'domicilio', dat_telefono AS 'telefono',
      dat_emailHR AS 'emailHR',dat_nomeHR AS 'nomeHR', dat_telefonoHR AS 'telefonoHR' from datore where dat_flag=1 order by dat_nome");
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
    <title>Datori</title>
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
    // funzione per la visibilità delle colonne quando i checkbox vengono cliccati
    $("label input").change(function(){
      var valore = this.value;
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

    $("#bInsert").click(function(){
      var n = 0;
      // svuota campi messaggio
      $("#messaggioNome").empty();
      $("#messaggioIndirizzo").empty();
      $("#messaggioDomicilio").empty();
      $("#messaggioTelefono").empty();
      $("#messaggioEmailHR").empty();
      $("#messaggioNomeHR").empty();
      $("#messaggioTelefonoHR").empty();

      // regex e creazioni variabili
      var regexTesto = /[a-z,A-Z]/;
      var regexDomicilio = /([0-9]\s[a-z,A-Z])/;
      var regexAlfa = /([0-9,a-z,A-Z])/;
      var regexTelefono = /[+,0-9]$/;
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
        n++;
      }

      if(regexAlfa.test(indirizzo)){
        $("#messaggioIndirizzo").append("il formato va bene");
      }
      else{
        $("#messaggioIndirizzo").append("inserire solo lettere o numeri");
        n++;
      }

      if(regexDomicilio.test(domicilio)){
        $("#messaggioDomicilio").append("il formato va bene");
      }
      else{
        $("#messaggioDomicilio").append("formato errato: CAP luogo");
        n++;
      }

      if(regexTelefono.test(telefono)){
        $("#messaggioTelefono").append("il formato va bene");
      }
      else{
        $("#messaggioTelefono").append("inserire un numero di telefono valido");
        n++;
      }

      if(regexAlfa.test(nomeHR) || nomeHR ==""){
        $("#messaggioNomeHR").append("il formato va bene");
      }
      else{
        $("#messaggioNomeHR").append("inserire solo lettere o numeri");
        n++;
      }

      if(regexEmail.test(emailHR) || emailHR ==""){
        $("#messaggioEmailHR").append("il formato va bene");
      }
      else{
        $("#messaggioEmailHR").append("inserire una email valida");
        n++
      }

      if(regexTelefono.test(telefonoHR) || telefonoHR ==""){
        $("#messaggioTelefonoHR").append("il formato va bene");
      }
      else{
        $("#messaggioTelefonoHR").append("inserire un numero di telefono valido");
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
    $("#messaggioIndirizzo").empty();
    $("#messaggioDomicilio").empty();
    $("#messaggioTelefono").empty();
    $("#messaggioEmailHR").empty();
    $("#messaggioNomeHR").empty();
    $("#messaggioTelefonoHR").empty();
  }

  </script>
  <body class="body">
    <?php include_once "menu.php";
    ?>
    <div class="container">
      <h1 class="col-xs-12">Datori</h1>
      <br>
      <div class="col-xs-12">
        <form method="post" action="datoriPDF.php" target="_blank">
          <input type="hidden" name="pdf"/>
          <label class="col-sm-2 col-xs-2 control-label">Ricerca: </label>
          <div class="col-sm-4 col-xs-10">
            <div class="input-group">
              <span class="input-group-addon glyphicon glyphicon-search"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
              <input type="text" class="form-control" id="search"></input>
            </div>
          </div>
          <?php if($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master"){ ?>
          <div class="col-sm-3 col-xs-6" style="margin-top: 0px;">
            <button type="button" class="btn btn-primary col-xs-12" data-toggle="modal" data-target="#myModalI" onclick="modalInsert()">
              <span class="glyphicon glyphicon-pencil"></span> inserisci
            </button>
          </div>
          <div class="col-sm-3 col-xs-6" style="margin-top:0px">
            <button type="submit" class="btn btn-primary col-xs-12">
              PDF
            </button>
          </div>
          <?php } ?>
        </div>
        <div class="col-xs-12">
          <button type="button" class="btn btn-default btn-md" id="bCheckbox">Nascondi Checkbox</button>
        </div>
        <div class="col-xs-12" id="checkbox">
          <label class="col-xs-3">nome: <input type="checkbox" name="nome" value="nome" checked="true" id="checknome"></label>
          <label class="col-xs-3">indirizzo: <input type="checkbox" name="indirizzo" value="indirizzo"  id="checkIndirizzo"></label>
          <label class="col-xs-3">domicilio: <input type="checkbox" name="domicilio" value="domicilio"  id="checkDomicilio"></label>
          <label class="col-xs-3">telefono: <input type="checkbox" name="telefono" value="telefono" checked="true" id="checktelefono"></label>
          <label class="col-xs-3">nome HR: <input type="checkbox" name="nomeHR" value="nomeHR"  id="checkNomeHR"></label>
          <label class="col-xs-3">email HR: <input type="checkbox" name="emailHR" value="emailHR"  id="checkEmailHR"></label>
          <label class="col-xs-3">telefono HR: <input type="checkbox" name="telefonoHR" value="telefonoHR"  id="checkTelefonoHR"></label>
        </div>
      </form>
      <div class="col-xs-6" id="errori">
      </div>
      <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="table" >
        <thead>
          <tr id="th">
            <th class="tbnome">Nome</th>
            <th class="tbindirizzo" style="display: none;">Indirizzo</th>
            <th class="tbdomicilio" style="display: none;">Domicilio</th>
            <th class="tbtelefono">Telefono</th>
            <th class="tbnomeHR" style="display: none;">nome HR</th>
            <th class="tbemailHR" style="display: none;">email HR</th>
            <th class="tbtelefonoHR"  style="display: none;">Telefono HR</th>
            <?php
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
          <tr>
            <td class="tbnome"><?php echo $row["nome"] ?></td>
            <td class="tbindirizzo" style="display: none;"><?php echo $row["indirizzo"] ?></td>
            <td class="tbdomicilio" style="display: none;"><?php echo $row["domicilio"] ?></td>
            <td class="tbtelefono"><?php echo $row["telefono"] ?></td>
            <td class="tbnomeHR"><?php echo $row["nomeHR"] ?></td>
            <td class="tbemailHR" style="display: none;"><?php echo $row["emailHR"] ?></td>
            <td class="tbtelefonoHR" style="display: none;"><?php echo $row["telefonoHR"] ?></td>
            <?php if($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master"){ ?>
            <td>
              <form method="post" action="datoriModifica.php">
                <button type="submit" name='buttonM' id="<?php echo $row['id']?>" class="btn btn-info btn-md">
                   <span class="glyphicon glyphicon-edit"></span>
                </button>
                <input type="hidden" name="modifica" value="<?php echo $row['id']?>" />
              </form>
            </td>
            <td>
              <button type="button" name='buttonD' id="<?php echo $row['id']?>" class="btn btn-danger btn-md"
                 data-toggle="modal" data-target="#myModalD" onclick="document.getElementById('pElimina').value='<?php echo $row['id']?>';">
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
                  <h4 class="modal-title">Eliminazione datore</h4>
                </div>
                <div class="modal-body">
                  <div class="alert alert-danger">
                    Sei sicuro di voler eliminare il datore?
                  </div>
                </div>
                <div class="modal-footer">
                  <form method="post" action="">
                    <button type="submit" class="btn btn-default">ok</button>
                    <input type="hidden" name="datoreCancellato" id="pElimina" required="required"/>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="container">
          <!-- Modal di inserimento-->
          <div class="modal fade" id="myModalI" role="dialog">
            <div class="modal-dialog modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 id="titoloInserimento" class="modal-title">Creazione di un datore </h4>
                </div>
                <div class="modal-body">
                  <form id="formInsert" method="post">
                    <div class="row">
                      <div id="nomeDiv" class="col-xs-6">
                        <label id="nomeLb">Nome:</label>
                        <input type="text" name="insertNome" id="insertNome" class="form-control" placeholder="nome del datore" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioNome"></div>
                      </div>
                      <div id="telefonoDiv" class="col-xs-6">
                        <label id="telefonoLb">Telefono:</label>
                        <input type="text" name="insertTelefono" id="insertTelefono" class="form-control" placeholder="es: 012 345 67 89" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioTelefono"></div>
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
                      <div id="nomeHRDiv" class="col-xs-6">
                        <label id="nomeHRLb">Nome e cognome HR:</label>
                        <input type="text" name="insertNomeHR" id="insertNomeHR" class="form-control" placeholder="nome cognome" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioNomeHR"></div>
                      </div>
                      <div id="emailHRDiv" class="col-xs-6">
                        <label id="emailHRLb">Email HR:</label>
                        <input type="email" name="insertEmailHR" id="insertEmailHR" class="form-control" placeholder="email del HR" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioEmailHR"></div>
                      </div>
                    </div>
                    <div class="row">
                      <div id="telefonoHRDiv" class="col-xs-6">
                        <label id="telefonoLb">Telefono HR:</label>
                        <input type="text" name="insertTelefonoHR" id="insertTelefonoHR" class="form-control" placeholder="es: 012 345 67 89" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioTelefonoHR"></div>
                      </div>
                    </div>
                    <input type="hidden" name="insert" value=""/>
                  </form>
                </div>
                <div class="modal-footer">
                  <div class="col-xs-4">
                  </div>
                  <button type="button" id="bInsert" class="btn btn-lg btn-default col-xs-4">Inserisci</button>
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
  echo "formatori.php";
}
?>
