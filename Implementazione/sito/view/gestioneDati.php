<!-- pagina per la gestione dei dati-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null) && $_SESSION["tipo"]=="master"){
  ?>
  <!DOCTYPE html>
  <html lang="it">
  <head>
    <title>gestione dati</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/script.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link href="css/stylebase.css" rel="stylesheet">

  </head>
  <script>
  // gestire email e gruppi, da controllare
  $(document).ready(function(){
    // quando il bottone per gli account viene cliccato
    $("#bCheckboxAccount").click(function(){
      $("#tbAccount").toggle();
      if($("#bCheckboxAccount").text()=="Nascondi Tabella Account"){
        $("#bCheckboxAccount").text("Visualizza Tabella Account");
      }
      else{
        $("#bCheckboxAccount").text("Nascondi Tabella Account");
      }
    });

    // quando il bottone per gli apprendisti viene cliccato
    $("#bCheckboxApprendista").click(function(){
      $("#tbApprendista").toggle();
      if($("#bCheckboxApprendista").text()=="Nascondi Tabella Apprendista"){
        $("#bCheckboxApprendista").text("Visualizza Tabella Apprendista");
      }
      else{
        $("#bCheckboxApprendista").text("Nascondi Tabella Apprendista");
      }
    });


    // quando il bottone per i formatori viene cliccato
    $("#bCheckboxFormatore").click(function(){
      $("#tbFormatore").toggle();
      if($("#bCheckboxFormatore").text()=="Nascondi Tabella Formatore"){
        $("#bCheckboxFormatore").text("Visualizza Tabella Formatore");
      }
      else{
        $("#bCheckboxFormatore").text("Nascondi Tabella Formatore");
      }
    });


    // quando il bottone per i datori viene cliccato
    $("#bCheckboxDatore").click(function(){
      $("#tbDatore").toggle();
      if($("#bCheckboxDatore").text()=="Nascondi Tabella Datore"){
        $("#bCheckboxDatore").text("Visualizza Tabella Datore");
      }
      else{
        $("#bCheckboxDatore").text("Nascondi Tabella Datore");
      }
    });


    // quando il bottone per gli gruppi di email viene cliccato
    $("#bCheckboxGruppoEmail").click(function(){
      $("#tbGruppoEmail").toggle();
      if($("#bCheckboxGruppoEmail").text()=="Nascondi Tabella Gruppo Email"){
        $("#bCheckboxGruppoEmail").text("Visualizza Tabella Gruppo Email");
      }
      else{
        $("#bCheckboxGruppoEmail").text("Nascondi Tabella Gruppo Email");
      }
    });

    // quando il bottone per le email viene cliccato
    $("#bCheckboxEmail").click(function(){
      $("#tbEmail").toggle();
      if($("#bCheckboxEmail").text()=="Nascondi Tabella Email"){
        $("#bCheckboxEmail").text("Visualizza Tabella Email");
      }
      else{
        $("#bCheckboxEmail").text("Nascondi Tabella Email");
      }
    });


  });

  </script>
  <body class="body" style="min-width:400px;">
    <?php include_once "menu.php";
    ?>
    <div class="container">
      <h1 class="col-xs-12">Gestione dati</h1>
      <br>
      <div class="col-xs-12" style="padding-left:0px">
        <div class="col-md-4 col-xs-12 col-sm-6" style="">
          <button type="button" class="btn btn-default btn-md col-xs-12" return="false" id="bCheckboxAccount">Nascondi Tabella Account</button>
        </div>
        <div class="col-md-4 col-xs-12 col-sm-6" style="">
          <button type="button" class="btn btn-default btn-md col-xs-12" return="false" id="bCheckboxApprendista">Nascondi Tabella Apprendista</button>
        </div>
        <div class="col-md-4 col-xs-12 col-sm-6" style="">
          <button type="button" class="btn btn-default btn-md col-xs-12" return="false" id="bCheckboxFormatore">Nascondi Tabella Formatore</button>
        </div>
        <div class="col-md-4 col-xs-12 col-sm-6" style="">
          <button type="button" class="btn btn-default btn-md col-xs-12" return="false" id="bCheckboxDatore">Nascondi Tabella Datore</button>
        </div>
        <div class="col-md-4 col-xs-12 col-sm-6" style="">
          <button type="button" class="btn btn-default btn-md col-xs-12" return="false" id="bCheckboxGruppoEmail">Nascondi Tabella Gruppo Email</button>
        </div>
        <div class="col-md-4 col-xs-12 col-sm-6" style="">
          <button type="button" class="btn btn-default btn-md col-xs-12" return="false" id="bCheckboxEmail">Nascondi Tabella Email</button>
        </div>
      </div>
      <div class="col-xs-12" id="errori">
      </div>
      <?php
      try{
        // creazione tabella account
        $query = $conn->prepare("SELECT ute_email AS 'email' from utente where ute_flag=0 order by ute_email");
        $query->execute();
      }
      catch(PDOException $e)
      {
      //  echo $e;
      }
      if($query->rowCount()!=0){
        ?>
        <div class="col-xs-12" id="tbAccount">
          <div class="col-xs-12">
            <label class="col-xs-12"> Account:</label>
          </div>
          <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="tableAccount" >
              <tr id="th">
                <th class="tbnome">Nome</th>
                <th class="">Ripristina</th>
                <th class="">Elimina</th>
              </tr>
              <?php
              while($row = $query->fetch(PDO::FETCH_ASSOC)){
                ?>
              <tr id="td">
                <td><?php echo $row['email'] ?></td>
                <td>
                  <button type="button" name='buttonM' id="<?php echo $row['email']?>" class="btn btn-success btn-md"
                    data-toggle="modal" data-target="#myModalR" onclick="document.getElementById('pRipristina').value='<?php echo "account/".$row['email'];?>';">
                    <span class="glyphicon glyphicon-edit"></span>
                  </button>
                </td>
                <td>
                  <button type="button" name='buttonD' id="<?php echo $row['email']?>" class="btn btn-danger btn-md"
                    data-toggle="modal" data-target="#myModalD" onclick="document.getElementById('pElimina').value='<?php echo "account/".$row['email'];?>';">
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>
                </td>
              </tr>
              <?php } ?>
          </table>
        </div>
        <?php } ?>

        <?php
        try{
          // creazione tabella apprendista
          $query = $conn->prepare("SELECT app_nome AS 'nome',app_idContratto AS 'contratto',app_annoFine AS 'fine', app_annoScolastico AS 'scolastico' from apprendista where app_flag=0 order by app_nome");
          $query->execute();
        }
        catch(PDOException $e)
        {
          echo $e;
        }
        if($query->rowCount()!=0){
          ?>
          <div class="col-xs-12" id="tbApprendista">
            <div class="col-xs-12">
              <label class="col-xs-12"> Apprendista:</label>
            </div>
            <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="tableApprendista" >
              <tr id="th">
                <th class="tbnome">Nome</th>
                <th class="">Ripristina</th>
                <th class="">Elimina</th>
              </tr>
              <?php
              while($row = $query->fetch(PDO::FETCH_ASSOC)){
              ?>
              <tr id="td">
                <td><?php echo $row['nome'] ?>
                </td>
                <td>
                  <button type="button" name='buttonM' id="<?php echo $row['nome']?>" class="btn btn-success btn-md"
                    data-toggle="modal" data-target="#myModalR" onclick="document.getElementById('pRipristina').value='<?php echo "apprendista/".$row['contratto']."-".$row['fine']."-".$row['scolastico'];?>';">
                    <span class="glyphicon glyphicon-edit"></span>
                  </button>
                </td>
                <td>
                  <button type="button" name='buttonD' id="<?php echo $row['nome'];?>" class="btn btn-danger btn-md"
                    data-toggle="modal" data-target="#myModalD" onclick="document.getElementById('pElimina').value='<?php echo "apprendista/".$row['contratto']."-".$row['fine']."-".$row['scolastico'];?>';">
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>
                </td>
              </tr>
              <?php } ?>
            </table>
          </div>
          <?php } ?>

          <?php
          try{
            // creazione tabella formatori
            $query = $conn->prepare("SELECT for_email AS 'email', for_nome AS 'nome' from formatore where for_flag=0 order by for_email");
            $query->execute();
          }
          catch(PDOException $e)
          {
            echo $e;
          }
          if($query->rowCount()!=0){
            ?>
            <div class="col-xs-12" id="tbFormatore">
              <div class="col-xs-12">
                <label class="col-xs-12">Formatore:</label>
              </div>
              <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="tableFormatore" >
                <tr id="th">
                  <th class="tbnome">Nome</th>
                  <th class="">Ripristina</th>
                  <th class="">Elimina</th>
                </tr>
                <?php
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                  ?>
                <tr id="td">
                  <td><?php echo $row['nome'] ?></td>
                  <td>
                    <button type="button" name='buttonM' id="<?php echo $row['email']?>" class="btn btn-success btn-md"
                      data-toggle="modal" data-target="#myModalR" onclick="document.getElementById('pRipristina').value='<?php echo "formatore/".$row['email'];?>';">
                      <span class="glyphicon glyphicon-edit"></span>
                    </button>
                  </td>
                  <td>
                    <button type="button" name='buttonD' id="<?php echo $row['email']?>" class="btn btn-danger btn-md"
                      data-toggle="modal" data-target="#myModalD" onclick="document.getElementById('pElimina').value='<?php echo "formatore/".$row['email'];?>';">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </td>
                </tr>
                <?php } ?>
              </table>
            </div>
            <?php } ?>


            <?php
            try{
              // creazione tabella datori
              $query = $conn->prepare("SELECT dat_nome AS 'nome',dat_id AS 'id' from datore where dat_flag=0 order by dat_nome");
              $query->execute();
            }
            catch(PDOException $e)
            {
              echo $e;
            }
            if($query->rowCount()!=0){
              ?>
              <div class="col-xs-12" id="tbDatore">
                <div class="col-xs-12">
                  <label class="col-xs-12">Datore:</label>
                </div>
                <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="tableDatore" >
                  <tr id="th">
                    <th class="tbnome">Nome</th>
                    <th class="">Ripristina</th>
                    <th class="">Elimina</th>
                  </tr>
                  <?php
                  while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    ?>
                  <tr id="td">
                    <td><?php echo $row['nome'] ?></td>
                    <td>
                      <button type="button" name='buttonM' id="<?php echo $row['id']?>" class="btn btn-success btn-md"
                        data-toggle="modal" data-target="#myModalR" onclick="document.getElementById('pRipristina').value='<?php echo "datore/".$row['id'];?>';">
                        <span class="glyphicon glyphicon-edit"></span>
                      </button>
                    </td>
                    <td>
                      <button type="button" name='buttonD' id="<?php echo $row['id']?>" class="btn btn-danger btn-md"
                        data-toggle="modal" data-target="#myModalD" onclick="document.getElementById('pElimina').value='<?php echo "datore/".$row['id'];?>';">
                        <span class="glyphicon glyphicon-remove"></span>
                      </button>
                    </td>
                  </tr>
                  <?php } ?>
                </table>
              </div>
              <?php } ?>

              <?php
              try{
                // creazione tabella gruppoEmail
                $query = $conn->prepare("SELECT grue_nome AS 'nome',grue_id AS 'id' from gruppoEmail where grue_flag=0 order by grue_nome");
                $query->execute();
              }
              catch(PDOException $e)
              {
                echo $e;
              }
              if($query->rowCount()!=0){
                ?>
                <div class="col-xs-12" id="tbGruppoEmail">
                  <div class="col-xs-12">
                    <label class="col-xs-12">Gruppo email:</label>
                  </div>
                  <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="tableGruppoEmail" >
                    <tr id="th">
                      <th class="tbnome">Nome</th>
                      <th class="">Ripristina</th>
                      <th class="">Elimina</th>
                    </tr>
                    <?php
                    while($row = $query->fetch(PDO::FETCH_ASSOC)){
                      ?>
                    <tr id="td">
                      <td><?php echo $row['nome'] ?></td>
                      <td>
                        <button type="button" name='buttonM' id="<?php echo $row['id']?>" class="btn btn-success btn-md"
                          data-toggle="modal" data-target="#myModalR" onclick="document.getElementById('pRipristina').value='<?php echo "gruppoEmail/".$row['id'];?>';">
                          <span class="glyphicon glyphicon-edit"></span>
                        </button>
                      </td>
                      <td>
                        <button type="button" name='buttonD' id="<?php echo $row['id']?>" class="btn btn-danger btn-md"
                          data-toggle="modal" data-target="#myModalD" onclick="document.getElementById('pElimina').value='<?php echo "gruppoEmail/".$row['id'];?>';">
                          <span class="glyphicon glyphicon-remove"></span>
                        </button>
                      </td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
                <?php } ?>

              <?php
              try{
                // creazione tabella email
                $query = $conn->prepare("SELECT ema_oggetto AS 'nome',ema_id AS 'id' from email where ema_flag=0 order by ema_oggetto");
                $query->execute();
              }
              catch(PDOException $e)
              {
                echo $e;
              }
              if($query->rowCount()!=0){
                ?>
                <div class="col-xs-12" id="tbEmail">
                  <div class="col-xs-12">
                    <label class="col-xs-12">Email:</label>
                  </div>
                  <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="tableEmail" >
                    <tr id="th">
                      <th class="tbnome">Nome</th>
                      <th class="">Ripristina</th>
                      <th class="">Elimina</th>
                    </tr>
                    <?php
                    while($row = $query->fetch(PDO::FETCH_ASSOC)){
                      ?>
                    <tr id="td">
                      <td><?php echo $row['nome'] ?></td>
                      <td>
                        <button type="button" name='buttonM' id="<?php echo $row['id']?>" class="btn btn-success btn-md"
                          data-toggle="modal" data-target="#myModalR" onclick="document.getElementById('pRipristina').value='<?php echo "email/".$row['id'];?>';">
                          <span class="glyphicon glyphicon-edit"></span>
                        </button>
                      </td>
                      <td>
                        <button type="button" name='buttonD' id="<?php echo $row['id']?>" class="btn btn-danger btn-md"
                          data-toggle="modal" data-target="#myModalD" onclick="document.getElementById('pElimina').value='<?php echo "email/".$row['id'];?>';">
                          <span class="glyphicon glyphicon-remove"></span>
                        </button>
                      </td>
                    </tr>
                    <?php } ?>
                  </table>
                </div>
                <?php } ?>

              <!-- modal -->
              <div class="container">
                <!-- Modal di eliminazione-->
                <div class="modal fade" id="myModalD" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Eliminazione definitiva</h4>
                      </div>
                      <div class="modal-body">
                        <div class="alert alert-danger">
                          Sei sicuro di volerlo eliminare? sarà eliminato definitivamente e non si potrà tornare indietro
                        </div>
                      </div>
                      <div class="modal-footer">
                        <form method="post" action="">
                          <button type="submit" class="btn btn-danger">ok</button>
                          <input type="hidden" name="cancellaDato" id="pElimina" required="required"/>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- modal -->
              <div class="container">
                <!-- Modal di ripristino-->
                <div class="modal fade" id="myModalR" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Ripristino</h4>
                      </div>
                      <div class="modal-body">
                        <div class="alert alert-success">
                          Sei sicuro di voler ripristinare il dato?
                        </div>
                      </div>
                      <div class="modal-footer">
                        <form method="post" action="">
                          <button type="submit" class="btn btn-success">ok</button>
                          <input type="hidden" name="ripristinaDato" id="pRipristina" required="required"/>
                        </form>
                      </div>
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
          echo "<script>location.href='gestioneDati.php'</script>";
        }
?>
