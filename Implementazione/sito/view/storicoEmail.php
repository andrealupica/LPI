<!-- pagina per la gestione degli apprendisti-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null) && ($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master") AND isset($_GET["id"]) AND is_numeric($_GET["id"]) ){
  ?>
  <!DOCTYPE html>
  <html lang="it">
  <head>
    <title>storico email</title>
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
  </script>
  <body class="body">
    <?php include_once "menu.php";
    ?>
    <div class="container">
      <h1 class="col-xs-12">storico email</h1>
      <br>
      <div class="col-xs-12" id="errori">
      </div>
      <?php
        try{
          $query1 = $conn->prepare("SELECT ute_email AS 'mittente',ema_messaggio AS 'messaggio',ema_data AS 'data',ema_oggetto AS 'oggetto',ema_flag AS 'flag' from email where ema_id=:id");
          $query1->bindParam(':id',$_GET["id"]);
          $query1->execute();
          $row1 = $query1->fetch(PDO::FETCH_ASSOC);
          if($row1['flag']==0){
              echo "<script>location.href='gestioneEmail.php'</script>";
          }
        }
        catch(PDOException $e)
        {
          echo $e;
        }
       ?>
      <div class="col-xs-12">
        <div class="col-md-8 col-xs-6">
          <label>Mittente: </label>
        </div>
        <div class="col-md-4 col-xs-6">
          <label>Ora di invio: </label>
        </div>
        <div class="col-xs-8">
          <input type="text" readonly="true" class="form-control" value="<?php echo $row1['mittente'];?>"/>
        </div>
        <div class="col-xs-4">
          <input type="text" readonly="true" class="form-control" value="<?php
          $dum = explode(' ',$row1['data']);
          $dato = explode('-', $dum[0]);
          echo $dato[2].'.'.$dato[1].'.'.$dato[0].' '.$dum[1];
          ?>"/>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="col-xs-12">
          <label>Destinatario: </label>
        </div>
        <div class="col-xs-12">
          <textarea type="text" readonly="true" class="form-control" ><?php
          try{
            $query = $conn->prepare("SELECT for_email AS 'email' from forema where ema_id=:id order by for_email");
            $query->bindParam(':id',$_GET["id"]);
            $query->execute();
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
              echo $row['email'].",";
            }
          }
          catch(PDOException $e)
          {
            echo $e;
          }
            ?>
          </textarea>
        </div>
      </div>
      <div class="col-xs-12">
        <div class="col-xs-12">
          <label>Oggetto:</label>
        </div>
        <div class="col-xs-7">
          <input type="text" required="true"  readonly="true" id="oggetto" name="oggetto" placeholder="Oggetto" class="form-control" value="<?php echo $row1['oggetto'] ?>"/>
        </div>
      </div>
      <div class="col-xs-12 col-md-8">
        <div class="col-xs-12">
          <label>Messaggio: </label>
        </div>
        <div class="col-xs-12">
          <textarea type="text" style="margin-top: 0px;" readonly="true"  required="true" name="messaggio" id="messaggio" placeholder="inserisci il messaggio" class="form-control"><?php echo $row1['messaggio'] ?></textarea>
        </div>
      </div>
      <div class="col-md-4 col-xs-12">
        <div class="col-xs-12">
          <label>Elimina: </label>
        </div>
        <div class="col-xs-12">
        </div>
        <button type="button" name='buttonD' class="btn btn-danger btn-lg col-xs-12"
           data-toggle="modal" data-target="#myModalD">
           <span class="glyphicon glyphicon-remove"></span>
        </button>
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
              <h4 class="modal-title">Eliminazione della email</h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-danger">
                Sei sicuro di voler eliminare l email?
              </div>
            </div>
            <div class="modal-footer">
              <form method="post" action="">
                <button type="submit" class="btn btn-default">ok</button>
                <input type="hidden" name="emailCancellata" id="pElimina" value="<?php echo $_GET["id"] ?>" required="required"/>
              </form>
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
