<!-- pagina per la gestione dei formatori-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null)){ // da riguardare
  try{
    $query = $conn->prepare("SELECT form.for_email AS 'email',form.for_nome AS 'nome', form.for_telefono AS 'telefono',dat.dat_nome AS 'datore' from formatore form
      join datore dat ON form.dat_id=dat.dat_id where form.for_flag=1 order by form.for_nome");
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
    <title>Formatori</title>
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
      // svuota campi messaggio
      $("#messaggioNome").empty();
      $("#messaggioTelefono").empty();
      $("#messaggioEmail").empty();
      // regex e creazioni variabili
      var regexTesto = /[a-z,A-Z]/;
      var regexAlfa = /([0-9,a-z,A-Z])/;
      var regexTelefono = /[+,0-9]$/;
      var regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

      nome = $("#insertNome").val();
      telefono = $("#insertTelefono").val();
      email = $("#insertEmail").val();
      datore = $("#datoreSel").val();

      if(regexTesto.test(nome)){
        $("#messaggioNome").append("il formato va bene");
      }
      else{
        $("#messaggioNome").append("formato errato: inserire solo lettere");
        n++;
      }

      if(regexTelefono.test(telefono)){
        $("#messaggioTelefono").append("il formato va bene");
      }
      else{
        $("#messaggioTelefono").append("inserire un numero di telefono valido");
        n++
      }

      if(regexEmail.test(email)){
        $("#messaggioEmail").append("il formato va bene");
      }
      else{
        $("#messaggioEmail").append("inserire una email valida");
        n++
      }

      if($("#datoreSel").val()<=0){
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
    $("#messaggioTelefono").empty();
    $("#messaggioEmail").empty();
  }

  </script>
  <body class="body">
    <?php include_once "menu.php";
    ?>
    <div class="container">
      <h1 class="col-xs-12">Formatori</h1>
      <br>
      <form method="post" action="formatoriPDF.php">
        <input type="hidden" name="pdf"/>
        <div class="col-xs-12">
          <label class="col-sm-2 col-xs-4 control-label">Ricerca: </label>
          <div class="col-sm-4 col-xs-8">
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
          <?php } ?>
          <div class="col-sm-3 col-xs-6" style="margin-top:0px">
            <button type="submit" class="btn btn-primary col-xs-12">
              PDF
            </button>
          </div>
        </div>
        <div class="col-xs-12">
          <button type="button" class="btn btn-default btn-md" id="bCheckbox">Nascondi Checkbox</button>
        </div>
        <div class="col-xs-12" id="checkbox">
          <label class="col-xs-3">nome: <input type="checkbox" name="nome" value="nome" checked="true" id="checknome"></label>
          <label class="col-xs-3">email: <input type="checkbox" name="email" value="email" checked="true" id="checkemail"></label>
          <label class="col-xs-3">telefono: <input type="checkbox" name="telefono" value="telefono" id="checktelefono"></label>
          <label class="col-xs-3">datore: <input type="checkbox" name="datore" value="datore" checked="true" id="checkddatore"></label>
        </div>
      </form>
      <div class="col-xs-6" id="errori">
      </div>
      <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="table" >
        <thead>
          <tr id="th">
            <th class="tbnome">Nome</th>
            <th class="tbemail">Email</th>
            <th class="tbtelefono" style="display: none;">Telefono</th>
            <th class="tbdatore">Datore</th>
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
          <tr>
            <td class="tbnome"><?php echo $row["nome"] ?></td>
            <td class="tbemail"><?php echo $row["email"] ?></td>
            <td class="tbtelefono" style="display: none;"><?php echo $row["telefono"] ?></td>
            <td class="tbdatore"><?php echo $row["datore"] ?></td>
            <td>
              <form method="post" action="formatoriDettaglio.php">
                <button type="submit" name='buttonD' id="<?php echo $row['email']?>" class="btn btn-primary btn-md">
                   <span class="glyphicon glyphicon-option-horizontal"></span>
                </button>
                <input type="hidden" name="dettaglio" value="<?php echo $row['email']?>" />
              </form>
            </td>
            <?php if($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master"){ ?>
            <td>
              <form method="post" action="formatoriModifica.php">
                <button type="submit" name='buttonM' id="<?php echo $row['email']?>" class="btn btn-info btn-md">
                   <span class="glyphicon glyphicon-edit"></span>
                </button>
                <input type="hidden" name="modifica" value="<?php echo $row['email']?>" />
              </form>
            </td>
            <td>
              <button type="button" name='buttonD' id="<?php echo $row['email']?>" class="btn btn-danger btn-md"
                 data-toggle="modal" data-target="#myModalD" onclick="document.getElementById('pElimina').value='<?php echo $row['email']?>';">
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
                  <h4 class="modal-title">Eliminazione formatore</h4>
                </div>
                <div class="modal-body">
                  <div class="alert alert-danger">
                    Sei sicuro di voler eliminare il formatore?
                  </div>
                </div>
                <div class="modal-footer">
                  <form method="post" action="">
                    <button type="submit" class="btn btn-default">ok</button>
                    <input type="hidden" name="formatoreCancellato" id="pElimina" required="required"/>
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
                  <form id="formInsert" method="post" target="_blank">
                    <div class="row">
                      <div id="nomeDiv" class="col-xs-6">
                        <label id="nomeLb">Nome e cognome:</label>
                        <input type="text" name="insertNome" id="insertNome" class="form-control" placeholder="nome cognome" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioNome"></div>
                      </div>
                      <div id="emailDiv" class="col-xs-6">
                        <label id="emailLb">Email:</label>
                        <input type="email" name="insertEmail" id="insertEmail" class="form-control" placeholder="email del formatore" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioEmail"></div>
                      </div>
                    </div>
                    <div class="row">
                      <div id="telefonoDiv" class="col-xs-6">
                        <label id="telefonoLb">Telefono:</label>
                        <input type="text" name="insertTelefono" id="insertTelefono" class="form-control" placeholder="es: 012 345 67 89" required="required"/>
                        <div class="col-xs-12 messaggio" id="messaggioTelefono"></div>
                      </div>
                      <div class="col-xs-6">
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
