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
    <script src="bootstrap-select/js/i18n/defaults-*.min.js"></script>

  </head>
  <script>
  </script>
  <body class="body" style="min-width:680px;">
    <?php include_once "menu.php";
    ?>
    <div class="container">
      <h1 class="col-xs-12">Gestione email</h1>
      <br>
      <div class="col-xs-12" id="errori">
      </div>
      <div class="col-xs-12">
        <div class="col-sm-4 col-xs-4" style="margin-top: 0px;">
          <button class="btn btn-primary col-xs-12" type="button" return="false" data-toggle="modal" data-target="#myModalI" onclick="modalInsert()">
            <span class="glyphicon  glyphicon-pencil"></span> Crea gruppo
          </button>
        </div>
        <div class="col-sm-4 col-xs-4" style="margin-top: 0px;">
          <button class="btn btn-primary col-xs-12" type="button" return="false" data-toggle="modal" data-target="#myModalI" onclick="modalInsert()">
            <span class="glyphicon glyphicon-edit"></span> Gestisci gruppo
          </button>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="col-xs-12">
          <label>Destinatario: </label>
        </div>
        <div class="col-xs-6">
            <select class="selectpicker" multiple name="selectDestinatario">
              <?php
              try{
                $query = $conn->prepare("SELECT for_nome AS 'nome',for_email AS 'email' from formatore where for_flag=1 order by for_nome");
                $query->execute();
              }
              catch(PDOException $e)
              {
                echo $e;
              }
              while($row = $query->fetch(PDO::FETCH_ASSOC)){
            ?>
              <option value="<?php echo $row["email"] ?>"><?php echo $row["nome"] ?></option>
            <?php
              }
            ?>
          </select>
        </div>
        <div class="col-xs-6">
        </div>
      </div>
      <div class="col-xs-12">
        <div class="col-xs-12">
          <label>Oggetto:</label>
        </div>
        <div class="col-xs-7">
          <input type="text" required="true" placeholder="Oggetto" class="form-control"/>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="col-xs-12">
          <label>Messaggio: </label>
        </div>
        <div class="col-xs-12">
          <textarea type="text" required="true" placeholder="inserisci il messaggio" class="form-control"></textarea>
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
              <form>
                <input type="text" class="form-control" required="required" placeholder="scegli un nome per il gruppo" style="margin-bottom:5px"/>
                <select class="selectpicker" multiple name="createGruppo" >
                  <?php
                  try{
                    $query = $conn->prepare("SELECT for_nome AS 'nome',for_email AS 'email' from formatore where for_flag=1 order by for_nome");
                    $query->execute();
                  }
                  catch(PDOException $e)
                  {
                    echo $e;
                  }
                  while($row = $query->fetch(PDO::FETCH_ASSOC)){
                ?>
                  <option value="<?php echo $row["email"] ?>"><?php echo $row["nome"] ?></option>
                <?php
                  }
                ?>
              </select>
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
  </body>
  </html>
  <?php
}
else{
  echo "<script>location.href='gestioneEmail.php'</script>";
}
?>
