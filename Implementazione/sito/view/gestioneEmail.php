<!-- pagina per la gestione degli apprendisti-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null) && ($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master")){
  ?>
  <!DOCTYPE html>
  <html lang="it">
  <head>
    <title>gestione email</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/script.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link href="css/stylebase.css" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="bootstrap-select/dist/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="bootstrap-select/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="bootstrap-select/js/i18n/defaults-it_IT.js"></script>

  </head>
  <script>
  $(document).ready(function(){
    $("#bCrea").click(function(){
        if($("#createGruppo").val()!=null && $("#nomeGruppo").val()!=""){
          alert("invia");
          $("#formCreazione").submit();
        }
        else{
          alert($("#createGruppo").val()+" "+ $("#nomeGruppo").val());
        }
    });

    $("#bInvia").click(function(){
      messaggio = $("#messaggio").val();
      oggetto = $("#oggetto").val();
      destinatari = $("#selectDestinatario").val();
      gruppo = $("#selectGruppo").val();
      if(messaggio!="" && oggetto != "" && (destinatari !=null || gruppo!=null)){
        //alert("invia: "+messaggio+" "+oggetto+" "+destinatari+" "+gruppo);
        $("#formInvia").submit();
      }
    });

    $("#bElimina").click(function(){
      if($("#gElimina").val()!=null || $("#gElimina")!=""){
        //alert($("#gElimina").val());
        $("#formModifica").submit();
      }
    });

    $("#bModifica").click(function(){
      if($("#gElimina").val()!=null || $("#gElimina")!=""){
        $("#gElimina").val("");
        $("#formModifica").submit();
      }
    });

    $("#gruppoModificato").change(function(){
      valore=$("#gruppoModificato").val();
      $("#gElimina").val(valore);
      $("#modificaGruppo").empty();
      $.ajax({
        type:"POST",
        url: "model/gestioneEmail2.php",
        data:{gruppoModificato:valore},
        success: function(result){
          // seleziono tutto i formatori e inoltre se sono presenti nel gruppo oppure no
          result=JSON.parse(result);
          for (var i = 0; i < result.length; i++) {
            risultato = result[i].split("/");
            if(risultato[2]==1){
              $("#modificaGruppo").append("<option selected='true' value='"+risultato[1]+"'>"+risultato[0]+"</option>");
            }
            else{
              $("#modificaGruppo").append("<option  value='"+risultato[1]+"'>"+risultato[0]+"</option>");
            }
            $('.selectpicker1').selectpicker('refresh');
          }
      }});
    });

    $("#storico").change(function(){
      id=$(this).val();
      url = "storicoEmail.php?id="+id;
      window.open(url, '_blank');
    });
  });
  </script>
  <body class="body">
    <?php include_once "menu.php";
    ?>
    <div class="container">
      <h1 class="col-xs-12">Gestione email</h1>
      <br>
      <div class="col-xs-12" id="errori">
      </div>
      <div class="col-xs-12">
        <div class="col-sm-4 col-xs-12" style="margin-top: 0px;">
          <button class="btn btn-primary col-xs-12" type="button" return="false" data-toggle="modal" data-target="#myModalI">
            <span class="glyphicon  glyphicon-pencil"></span> Crea gruppo
          </button>
        </div>
        <div class="col-sm-4 col-xs-12" style="margin-top: 0px;">
          <button class="btn btn-primary col-xs-12" type="button" return="false" data-toggle="modal" data-target="#myModalM" >
            <span class="glyphicon glyphicon-edit"></span> Gestisci gruppo
          </button>
        </div>
        <div class="col-md-4 col-xs-12" style="padding-left:0px;margin-top:0px">
          <select class="selectpicker col-xs-12" name="storico" id="storico">
              <option disabled selected value id="selectNull"> -- storico email -- </option>
              <?php
              try{
                $query = $conn->prepare("SELECT ema_oggetto AS 'oggetto',ema_id AS 'id' from email where ema_flag=1 order by ema_oggetto");
                $query->execute();
              }
              catch(PDOException $e)
              {
              //  echo $e;
              }
              while($row = $query->fetch(PDO::FETCH_ASSOC)){
            ?>
              <option value="<?php echo $row["id"] ?>"><?php echo $row["oggetto"] ?></option>
            <?php
              }
            ?>
          </select>
        </div>
      </div>
      <form method="post" id="formInvia">
        <input type="hidden" name="inviaInput" value="invia" id="inviaInput"/>
        <div class="col-xs-12">
          <div class="col-xs-12">
            <label>Destinatario: </label>
          </div>
          <div class="col-md-6 col-xs-12" style="padding-left:0px;">
            <select class="selectpicker col-xs-12" multiple data-actions-box="true" name="selectDestinatario[]" id="selectDestinatario">
                <?php
                try{
                  $query = $conn->prepare("SELECT for_nome AS 'nome',for_email AS 'email' from formatore where for_flag=1 order by for_nome");
                  $query->execute();
                }
                catch(PDOException $e)
                {
                //  echo $e;
                }
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
              ?>
                <option value="<?php echo $row["email"] ?>"><?php echo $row["nome"] ?></option>
              <?php
                }
              ?>
            </select>
          </div>
          <div class="col-xs-12 col-md-6" style="padding-left:0px">
            <select class="selectpicker col-xs-12" multiple data-actions-box="true" name="selectGruppo[]" id="selectGruppo">
                <?php
                try{
                  $query = $conn->prepare("SELECT grue_nome AS 'nome', grue_id AS 'id' from gruppoEmail where grue_flag=1 order by grue_nome");
                  $query->execute();
                }
                catch(PDOException $e)
                {
                //  echo $e;
                }
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
              ?>
                <option value="<?php echo $row["id"] ?>"><?php echo $row["nome"] ?></option>
              <?php
                }
              ?>
            </select>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="col-xs-12">
            <label>Oggetto:</label>
          </div>
          <div class="col-xs-7">
            <input type="text" required="true" required="true" id="oggetto" name="oggetto" placeholder="Oggetto" class="form-control"/>
          </div>
        </div>
        <div class="col-xs-12">
          <div class="col-xs-12">
            <label>Messaggio: </label>
          </div>
          <div class="col-md-8 col-xs-12">
            <textarea type="text" style="margin-top: 0px;" required="true" required="true" name="messaggio" id="messaggio" placeholder="inserisci il messaggio" class="form-control"></textarea>
          </div>
        </form>
        <div class="col-xs-12 col-md-2">
          <button type="button" id="bInvia" required="true" class="btn btn-primary col-xs-12">Invia</button>
        </div>
      </div>
    </div>
    <!-- modal -->
    <div class="container">
      <!-- Modal di eliminazione-->
      <div class="modal fade" id="myModalD" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Eliminazione gruppo</h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-danger">
                Sei sicuro di voler eliminare il gruppo?
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
              <h4 id="titoloInserimento" class="modal-title">Creazione di un gruppo di email </h4>
            </div>
            <div class="modal-body">
              <form method="post" id="formCreazione">
                <div class="row">
                    <div class="col-xs-12">
                      <input type="text" class="form-control" required="required" placeholder="scegli un nome per il gruppo"
                                    name="nomeGruppo" id="nomeGruppo" style="margin-bottom:5px"/>
                    </div>
                    <select class="selectpicker col-xs-8" multiple data-actions-box="true" name="createGruppo[]" id="createGruppo">
                    <?php
                    try{
                      $query = $conn->prepare("SELECT for_nome AS 'nome',for_email AS 'email' from formatore where for_flag=1 order by for_nome");
                      $query->execute();
                    }
                    catch(PDOException $e)
                    {
                      //echo $e;
                    }
                    while($row = $query->fetch(PDO::FETCH_ASSOC)){
                  ?>
                    <option value="<?php echo $row["email"] ?>"><?php echo $row["nome"] ?></option>
                  <?php
                    }
                  ?>
                </select>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <div class="col-xs-4">
              </div>
              <button type="submit" id="bCrea" class="btn btn-lg btn-default col-xs-4">Crea gruppo</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <!-- Modal di modifica-->
      <div class="modal fade" id="myModalM" role="dialog">
        <div class="modal-dialog modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 id="" class="modal-title">Modifica di un gruppo di email </h4>
            </div>
            <div class="modal-body">
              <form method="post" id="formModifica">
                <div class="row">
                    <div class="col-xs-12">
                      <select class="selectpicker col-xs-12"  data-actions-box="true" name="gruppoModificato" id="gruppoModificato">
                        <option disabled selected value> -- seleziona un gruppo -- </option>
                        <?php
                        try{
                          $query = $conn->prepare("SELECT grue_nome AS 'nome',grue_id AS 'id' from gruppoEmail where grue_flag=1 order by grue_nome");
                          $query->execute();
                        }
                        catch(PDOException $e)
                        {
                          //echo $e;
                        }
                        while($row = $query->fetch(PDO::FETCH_ASSOC)){
                      ?>
                        <option value="<?php echo $row["id"] ?>"><?php echo $row["nome"] ?></option>
                      <?php
                        }
                      ?>
                      </select>
                    </div>
                    <div class="col-xs-12">
                      <select class="col-xs-12 selectpicker selectpicker1"  multiple name="modificaGruppo[]" id="modificaGruppo">
                      </select>
                    </div>
                </div>
                <input type="hidden" name="eliminaGruppo" id="gElimina" value=""/>
              </form>
            </div>
            <div class="modal-footer">
              <div class="col-xs-2">
              </div>
              <button type="submit" id="bModifica" class="btn btn-lg btn-default col-xs-4">modifica gruppo</button>
              <button type="submit" id="bElimina" class="btn btn-lg btn-danger col-xs-4">elimina gruppo</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  </html>
  <?php
}
else{
  echo "<script>location.href='gestioneEmail.php'</script>";
}
?>
