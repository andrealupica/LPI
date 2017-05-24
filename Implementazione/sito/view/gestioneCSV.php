<!-- pagina per la gestione del file csv-->
<?php
if(($_SESSION['email']!="" OR $_SESSION['email']!=null) && ($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master")){
  try{
    $query = $conn->prepare("SELECT fil_nome AS 'nome', ute_email AS 'email' from file_;");
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
    <title>gestione pdf</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="js/script.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link href="css/stylebase.css" rel="stylesheet">

  </head>
  <script>
  $(document).ready(function(){

  });
  </script>
  <body class="body" style="min-width:500px">
    <?php include_once "menu.php";
    ?>
    <div class="container">
      <h1 class="col-xs-12">Gestione CSV</h1>
      <br>
      <div class="col-xs-12" id="errori">
      </div>
      <div class="col-xs-12" style="margin-bottom:10px">
        <form role="form" action="" method="post" name="form1" enctype="multipart/form-data">
           <fieldset>
               <input name="idCSV" type="file" id="idCSV" accept=".csv" />
               <input type="submit" name="Import" value="Importa " class="btn btn-primary" />
           </fieldset>
        </form>
      </div>
      <table data-role="table" data-mode="columntoggle" class="ui-responsive table table-striped table-bordered" id="table" >
        <thead>
          <tr>
            <th class="">Nome file</th>
            <th class="">Email autore</th>
            <th class="">Download</th>
            <th class="">PDF</th>
          </tr>
        </thead>
        <tbody>
        <?php
          while($row = $query->fetch(PDO::FETCH_ASSOC)){
          ?>
          <tr>
            <td><?php echo $row["nome"] ?></td>
            <td><?php echo $row["email"] ?></td>
            <td>
              <form method="post">
                <button type="submit" name='buttonD' id="<?php echo $row['nome']?>" class="btn btn-primary btn-md">
                   <span class="glyphicon glyphicon-download"></span>
                </button>
                <input type="hidden" name="download" value="<?php echo $row['nome']?>" />
              </form>
            </td>
            <td>
              <form method="post" action="csvPDF.php" target="_blank">
                <button type="submit" name='buttonPDF' id="<?php echo $row['nome']?>" class="btn btn-primary btn-md">
                   <span class="glyphicon glyphicon-floppy-saved"></span>
                </button>
                <input type="hidden" name="filecsv" value="<?php echo $row['nome']?>" />
              </form>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </body>
  </html>
  <?php
}
else{
  echo "<script>location.href='index.php'</script>";
}
?>
