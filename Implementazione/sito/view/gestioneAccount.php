<!-- pagina per la gestione degli accessi di tutti -->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null) AND ($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master")){ // da riguardare
  try{
    $query = $conn->prepare("SELECT ute_email AS 'email',ute_tipo AS 'tipo' from utente where ute_flag=1");
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
    <title>Gestione Account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/script.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link href="css/stylebase.css" rel="stylesheet">
  </head>
  <script>
  $(document).ready(function(){
    $("#bSaveModify").click(function(){
      //alert($("#password").val()+":"+$("#repassword").val());
      if($("#password").val()!=$("#repassword").val()){
        //alert("password diverse ");
        $("#messaggioModify").append("attenzione le password sono diverse");
        $("#messaggioModify").addClass("alert alert-warning");
      }
      else{
        $("#formModify").submit();
        $("#messaggioModify").empty();
        $("#messaggioModify").removeClass("alert alert-warning");
        $("#titoloModifica").empty();
      }
    });

    $("#bInsert").click(function(){
      $("#messaggioInsert").empty();
      email=$("#idEmailInsert").val();
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      if(regex.test(email)){
        $("#formInsert").submit();
        alert($("#idEmailInsert").val());
        $("#idEmailInsert").empty();
      }
      else{
        $("#messaggioInsert").append("la password non è valida");
      }
    });
  });
  function modalInsert(){
    $("#messaggioInsert").empty();
  }
  function selectEmail(obj){
		id = obj.id;
    id=id.split("/");
    email=id[0];
    tipo=id[1];
    $("#titoloModifica").empty();
    $("#titoloModifica").append("modifica di "+email);
    if(tipo==1){
      $("#adminCheckbox").prop('checked', true);
    }
    else{
      $("#adminCheckbox").prop('checked', false);
    }
    $("#messaggioModify").empty();
    $("#password").empty();
    $("#repassword").empty();
  }

  </script>
  <body class="body">
    <?php include_once "menu.php";
    ?>
    <div class="container">
      <h1 class="col-xs-12">Gestione Account</h1>
      <br>
      <div class="col-xs-12">
        <label class="col-sm-3 col-xs-4 control-label">Ricerca con email:</label>
        <div class="col-sm-6 col-xs-8">
          <div class="input-group">
            <span class="input-group-addon glyphicon glyphicon-search"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
            <input type="text" class="form-control" id="search"></input>
          </div>
        </div>
        <div class="col-sm-3 col-xs-12">
          <button class="btn btn-primary col-xs-12" data-toggle="modal" data-target="#myModalI" onclick="modalInsert()">
            <span class="glyphicon glyphicon-log-in"></span> registra utente
          </button>
        </div>
      </div>
        <table class="table" id="table" style="margin-top:10px">
          <thead>
            <tr>
              <th>Email</th>
              <th>Tipo</th>
              <th>Modifica</th>
              <th>Cancella</th>
            </tr>
          </thead>
          <tbody>
            <!--inserimento dati tramite php-->
            <?php
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
              ?>
              <tr>
                <td><?php echo $row["email"];?></td>
                <!--<td><?php echo ($row["tipo"]==2)?"master":($row["tipo"]==1)?"amministratore":"utente normale";echo $row["tipo"];?></td>-->
                <td><?php if($row["tipo"]==2)echo "master";elseif($row["tipo"]==1) echo "amministratore";else echo "utente normale";?></td>
                <td>
                  <?php if($_SESSION["email"]==$row["email"]){ // se utente loggato non mostrare
                  }
                  else if($row['tipo']==0){ // se riga è normale mostra
                    ?>
                    <button type="button" name='button' id="<?php echo $row['email']."/".$row["tipo"];?>" class="btn btn-info btn-md"
                       data-toggle="modal" data-target="#myModalM" onclick="selectEmail(this);document.getElementById('pModifica').value='<?php echo $row['email'];?>';">
                      <span class="glyphicon glyphicon-edit"></span>
                    </button>
                    <?php
                  }
                  else if($row['tipo']==1 AND $_SESSION["tipo"]=="admin"){ // se utente è admin e anche la riga non mostrare
                  }
                  else if($row['tipo']==1 AND $_SESSION["tipo"]=="master"){ // se utente è master e riga admin mostra
                    ?>
                    <button type="button" name='button' id="<?php echo $row['email']."/".$row["tipo"];?>" class="btn btn-info btn-md"
                      data-toggle="modal" data-target="#myModalM" onclick="selectEmail(this);document.getElementById('pModifica').value='<?php echo $row['email'];?>';">
                      <span class="glyphicon glyphicon-edit"></span>
                    </button>
                    <?php
                  }
                  else if($row['tipo']==2){ // se utente è master non mostrare
                  }
                  ?>
                </td>
                <td>
                  <?php if($_SESSION["email"]==$row["email"]){ // se utente loggato non mostrare
                  }
                  else if($row['tipo']==0){ // se riga è normale mostra
                    ?>
                    <button type="button" name='button' id="<?php echo $row['email'];?>" class="btn btn-danger btn-md"
                      data-toggle="modal" data-target="#myModal" onclick="document.getElementById('pEliminazione').value='<?php echo $row['email'];?>';">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                    <?php
                  }
                  else if($row['tipo']==1 AND $_SESSION["tipo"]=="admin"){ // se utente è admin e anche la riga non mostrare
                  }
                  else if($row['tipo']==1 AND $_SESSION["tipo"]=="master"){ // se utente è master e riga admin mostra
                    ?>
                    <button type="button" name='button' id="<?php echo $row['email'];?>" class="btn btn-danger btn-md"
                      data-toggle="modal" data-target="#myModal" onclick="document.getElementById('pEliminazione').value='<?php echo $row['email'];?>';">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                    <?php
                  }
                  else if($row['tipo']==2){ // se utente è master non mostrare
                  }
                  ?>
                </td>
              </tr>
              <?php
            }
            ?>
          </tbody>
        </table>
        <div>
        </div>
      <div class="container">
        <!-- Modal di eliminazione-->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Eliminazione account</h4>
              </div>
              <div class="modal-body">
                <div class="alert alert-danger">
                  Sei sicuro di voler eliminare l'account?
                </div>
              </div>
              <div class="modal-footer">
                <form method="post" action="">
                  <button type="submit" class="btn btn-default">ok</button>
                  <input type="hidden" name="emailCancellata" id="pEliminazione" required="required"/>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <!-- Modal di modifica-->
        <div class="modal fade" id="myModalM" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="titoloModifica" class="modal-title">Modifica dell'account di </h4>
              </div>
              <div class="modal-body">
                <form id="formModify" method="post">
                  <input type="hidden" name="emailModificata" id="pModifica" required="required"/>
                  <label>
                    <label>Amministratore:<input type="checkbox" name="adminCheckbox" id="adminCheckbox" value="admin" style="float:right"/></label>
                    <div class="form-group inputForm">
                      <label for="password" class="cols-sm-2 control-label">Password:</label>
                      <div class="cols-sm-10">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                          <input type="password" class="form-control" name="password" id="password" placeholder="inserire la nuova password"/>
                        </div>
                      </div>
                    </div>
                    <div class="form-group inputForm">
                      <label for="password" class="cols-sm-2 control-label">Ripeti password:</label>
                      <div class="cols-sm-10">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                          <input type="password" class="form-control" name="repassword" id="repassword"  placeholder="ripeti la nuova password"/>
                        </div>
                      </div>
                    </div>
                  </label>
                </form>
              </div>
              <div class="modal-footer">
                <div class="col-xs-6" id="messaggioModify"></div>
                <div class="col-xs-1"></div>
                <button type="submit" id="bSaveModify" class="btn btn-lg btn-default col-xs-5">salva</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <!-- Modal di inserimento-->
        <div class="modal fade" id="myModalI" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 id="titoloInserimento" class="modal-title">Creazione di un account </h4>
              </div>
              <div class="modal-body">
                <form id="formInsert" method="post">
                  <input type="email" name="emailInsert" id="idEmailInsert" class="form-control" required="required"/>
                  <label class="alert alert-info" style="margin-top:10px">in seguito verrà inviata un'email con le credenziali</label>
                </form>
              </div>
              <div class="modal-footer">
                <div class="col-xs-6" id="messaggioInsert"></div>
                <div class="col-xs-1"></div>
                <button type="submit" id="bInsert" class="btn btn-lg btn-default col-xs-5">Registra</button>
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
