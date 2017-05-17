<!-- pagina per la modifica dei formatori-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null)  && ($_SESSION['tipo']=="master" OR $_SESSION['email']=="admin") && isset($_POST["modifica"])){
  //email
  $mod = $_POST["modifica"];
  try{
    $query = $conn->prepare("SELECT form.for_email AS 'email',form.for_nome AS 'nome', form.for_telefono AS 'telefono',dat.dat_nome AS 'datore',dat.dat_id AS 'dat_id',form.for_osservazioni AS 'osservazioni' from formatore form
      join datore dat ON form.dat_id=dat.dat_id where form.for_flag=1 and form.for_email =:email");
    $query->bindParam(':email',$mod);
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
    <title>Formatori Modifica</title>
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

    // quando clicco su salva
    $("#bSalva").click(function(){
      var n = 0;
      // svuota campi messaggio
      $("#messaggioNome").empty();
      $("#messaggioTelefono").empty();
      $("#messaggioEmail").empty();

      // regex e creazioni variabili
      var regexTesto = /[a-z,A-Z]/;
      var regexData = /(\d{2})\.(\d{2})\.(\d{4})+$/;
      var regexContratto = /(\d{4})\.(\d{4})+$/;
      var regexDomicilio = /([0-9]\s[a-z,A-Z])/;
      var regexAlfa = /([0-9,a-z,A-Z])/;
      var regexAnno = /(\d{4})+$/;
      var regexNumero = /(\d{1})+$/;
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
      if(regexTelefono.test(telefono) OR telefono==""){
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
      // se non ci sono errori di formattazione submitta
      if(n==0){
        $("#formInsert").submit();
      }
      else{
      }
    });
  });

  </script>
  <body class="body">
    <?php include_once "menu.php";
      $row = $query->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="container">
      <h1 class="col-xs-12">Formatori Modifica</h1>
      <br>
      <form id="formInsert" method="post">
        <div class="row">
          <div id="nomeDiv" class="col-xs-6">
            <label id="nomeLb">Nome e cognome:</label>
            <input type="text" name="insertNome" id="insertNome" class="form-control" value="<?php echo $row["nome"]?> "placeholder="nome cognome" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioNome"></div>
          </div>
          <div id="emailDiv" class="col-xs-6">
            <label id="emailLb">Email:</label>
            <input type="email" name="insertEmail" id="insertEmail" readonly="true" class="form-control" value="<?php echo $row["email"] ?>" placeholder="email del formatore" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioEmail"></div>
          </div>
        </div>
        <div class="row">
          <div id="telefonoDiv" class="col-xs-6">
            <label id="telefonoLb">Telefono:</label>
            <input type="text" name="insertTelefono" id="insertTelefono" class="form-control" value="<?php echo $row["telefono"] ?>" placeholder="es: 012 345 67 89" required="required"/>
            <div class="col-xs-12 messaggio" id="messaggioTelefono"></div>
          </div>
          <div class="col-xs-6" >
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
                  if($row["dat_id"]==$r["id"]){
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
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <label id="osservazioniLb">Osservazioni:</label>
            <textarea class="form-control" name="osservazioni" style="margin-top:0px" ><?php echo $row["osservazioni"]?>
            </textarea>
            <div class="col-xs-12 messaggio" id=""></div>
          </div>
        </div>
        <input type="hidden" name="modifica" value="<?php echo $row["email"];?>"/>
        <input type="hidden" name="dati" value="dati"/>
      </form>
      <div class="col-xs-3"></div>
      <div class="col-xs-9">
        <button type="button" id="bSalva" class="btn btn-lg btn-default col-xs-4 col-xs-6">Salva</button>
      </div>
      <div class="col-xs-6" id="errori">
      </div>
    </div>
  </body>
  </html>
  <?php
}
else{
      echo "<script>location.href='formatori.php'</script>";
}
?>
