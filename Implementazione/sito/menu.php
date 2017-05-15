<script>
// funzione per il settaggio corretto del menu attivo nelle diverse pagine
  $(document).ready(function(){
    var url = window.location;
    $('ul.nav a[href="' + url + '"]').parent().addClass('active');
    $('ul.nav a').filter(function () {
        return this.href == url;
    }).parent().addClass('active');
  });
</script>
<?php
session_start();
if(isset($_SESSION["email"])){?>
  <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="apprendisti.php">Apprendisti Info & Media</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="apprendisti.php">Apprendisti</a></li>
        <li><a href="formatori.php">Formatori</a></li>
        <li><a href="#">Datori di lavoro</a></li>
        <?php
          if(($_SESSION["tipo"]=="admin" OR $_SESSION["tipo"]=="master")){
        ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gestione <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="gestioneAccount.php">Account</a></li>
            <li><a href="#">Email</a></li>
            <?php
             if($_SESSION["tipo"]=="master"){ ?>
            <li><a href="#">Dati</a></li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
  <?php
}

?>
