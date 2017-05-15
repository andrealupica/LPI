<?php
session_start();
if(isset($_SESSION["email"]) AND isset($_POST["pdf"])){
  include_once "connection.php";
  require_once('tcpdf/tcpdf.php'); // libreria per la creazione del pdf

  $gruppo = $_POST["gruppo"];
  $colonne = "";
  $where = "";
  // se è checckato il contratto
  if(isset($_POST["contratto"])){
    $colonne.="app.app_idContratto AS 'contratto',";
  }
  // se è checckato il nome
  if(isset($_POST["nome"])){
    $colonne.="app.app_nome AS 'apprendista',";
  }
  // se è checckato la data di nascita
  if(isset($_POST["dataNascita"])){
    $colonne.="app.app_dataNascita AS 'dataNascita',";
  }
  // se è checckato il telefono
  if(isset($_POST["telefono"])){
    $colonne.="app.app_telefono AS 'telefono',";
  }
  // se è checckato l indirizzo
  if(isset($_POST["indirizzo"])){
    $colonne.="app.app_indirizzo AS 'indirizzo',";
  }
  // se è checckato il domicilio
  if(isset($_POST["domicilio"])){
    $colonne.="app.app_domicilio AS 'domicilio',";
  }
  // se è checckato lo statuto
  if(isset($_POST["statuto"])){
    $colonne.="app.app_statuto AS 'statuto',";
  }
  // se è checckato il rappresentante
  if(isset($_POST["rappresentante"])){
    $colonne.="app.app_rappresentante AS 'rappresentante',";
  }
  // se è checckato la professione
  if(isset($_POST["professione"])){
    $colonne.="app.app_professione AS 'professione',";
  }
  // se è checckatola sede
  if(isset($_POST["sede"])){
    $colonne.="sed.sed_nome AS 'sede',";
  }
  // se è checckato la data di inizio
  if(isset($_POST["dataInizio"])){
    $colonne.="app.app_dataInizio AS 'dataInizio',";
  }
  // se è checckato la data di fine
  if(isset($_POST["dataFine"])){
    $colonne.="app.app_annoFine AS 'dataFine',";
  }
  // se è checckato l anno scolastico
  if(isset($_POST["annoScolastico"])){
    $colonne.="app.app_annoScolastico AS 'annoScolastico',";
  }
  // se è checckato il datore
  if(isset($_POST["datore"])){
    $colonne.="dat.dat_nome AS 'datore',";
  }
  // se è checckato il formatore
  if(isset($_POST["formatore"])){
    $colonne.="form.for_nome AS 'formatore',";
  }
  // cancella l'ultima virgola della SELECT
  $colonne=substr($colonne,0,count($colonne)-2);

  //echo $colonne."<br>";
  //echo "ricerca: ".$_POST["ricerca"];
  // filtraggio della barra di ricerca tramite where
  $where.=" where ";
  if(!empty($_POST["ricerca"])){
    $ricerca = $_POST["ricerca"];
    $option = explode(" ",$ricerca);
    for($i=0;$i<count($option);$i++){
      $where .="(app.app_idContratto LIKE '%".$option[$i]."%' OR app.app_nome LIKE '%".$option[$i]."%' OR app.app_telefono LIKE '%".$option[$i]."%'
      OR app.app_dataNascita LIKE '%".$option[$i]."%' OR   app.app_rappresentante LIKE '%".$option[$i]."%' OR app.app_statuto LIKE '%".$option[$i]."%'
      OR app.app_indirizzo LIKE '%".$option[$i]."%' OR app.app_domicilio LIKE '%".$option[$i]."%' OR app.app_osservazioni LIKE '%".$option[$i]."%' OR
      app.app_professione LIKE '%".$option[$i]."%' OR app.app_flag LIKE '%".$option[$i]."%' OR app.app_annoScolastico LIKE '%".$option[$i]."%' OR
      app.app_annoFine LIKE '%".$option[$i]."%' OR app.app_dataInizio LIKE '%".$option[$i]."%'
      OR sed.sed_nome LIKE '%".$option[$i]."%' OR dat.dat_nome LIKE '%".$option[$i]."%' OR form.for_email LIKE '%".$option[$i]."%')";
      $where.=" AND ";
    }
  }
  if($gruppo!=0){
    $where.= " grui_id = ".$gruppo." AND";
  }
  //echo $where."<br>";
  $completeQuery = "SELECT ".$colonne." FROM apprendista app
  JOIN datore dat ON dat.dat_id = app.dat_id
  JOIN formatore form ON form.for_email = app.for_email
  JOIN sede sed ON sed.sed_id = app.sed_id"
  .$where." app.app_flag=1 order by app.app_nome";

  //echo $completeQuery."<br>";

  $query = $conn->prepare($completeQuery);
  $query->execute();


  $html = '
  <table border="1" cellpadding="5">
    <thead>
      <tr>';
  if(isset($_POST["contratto"])){
    $html .= '<th>Contratto:</th>';
  }
  if(isset($_POST["nome"])){
    $html .= '<th>Nome Apprendista:</th>';
  }
  if(isset($_POST["dataNascita"])){
    $html .= '<th>Data di Nascita:</th>';
  }
  if(isset($_POST["telefono"])){
    $html .= '<th>Telefono:</th>';
  }
  if(isset($_POST["indirizzo"])){
    $html .= '<th>Indirizzo:</th>';
  }
  if(isset($_POST["domicilio"])){
    $html .= '<th>Domicilio:</th>';
  }
  if(isset($_POST["statuto"])){
    $html .= '<th>Statuto:</th>';
  }
  if(isset($_POST["rappresentante"])){
    $html .= '<th>Rappresentante:</th>';
  }
  if(isset($_POST["sede"])){
    $html .= '<th>Sede:</th>';
  }
  if(isset($_POST["dataInizio"])){
    $html .= '<th>Data di inizio:</th>';
  }
  if(isset($_POST["dataFine"])){
    $html .= '<th>Data di fine:</th>';
  }
  if(isset($_POST["annoScolastico"])){
    $html .= '<th>Anno scolastico:</th>';
  }
  if(isset($_POST["datore"])){
    $html .= '<th>Datore:</th>';
  }
  if(isset($_POST["formatore"])){
    $html .= '<th>Formatore:</th>';
  }

    $html.="</tr>";
  while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html.="<tr>";
    if(isset($_POST["contratto"])){
      $html.="<td>".$row['contratto']."</td>";
    }
    if(isset($_POST["nome"])){
      $html.="<td>".$row['apprendista']."</td>";
    }
    if(isset($_POST["dataNascita"])){
      $html.="<td>".$row['dataNascita']."</td>";
    }
    if(isset($_POST["telefono"])){
      $html.="<td>".$row['telefono']."</td>";
    }
    if(isset($_POST["indirizzo"])){
      $html.="<td>".$row['indirizzo']."</td>";
    }
    if(isset($_POST["domicilio"])){
      $html.="<td>".$row['domicilio']."</td>";
    }
    if(isset($_POST["statuto"])){
      $html.="<td>".$row['statuto']."</td>";
    }
    if(isset($_POST["rappresentante"])){
      $html.="<td>".$row['rappresentante']."</td>";
    }
    if(isset($_POST["sede"])){
      $html.="<td>".$row['sede']."</td>";
    }
    if(isset($_POST["dataInizio"])){
      $html.="<td>".$row['dataInizio']."</td>";
    }
    if(isset($_POST["dataFine"])){
      $html.="<td>".$row['dataFine']."</td>";
    }
    if(isset($_POST["annoScolastico"])){
      $html.="<td>".$row['annoScolastico']."</td>";
    }
    if(isset($_POST["datore"])){
      $html.="<td>".$row['datore']."</td>";
    }
    if(isset($_POST["formatore"])){
      $html.="<td>".$row['formatore']."</td>";
    }
    $html.="</tr>";
  }

  $html .='
    </thead>
    <tbody>

    </tbody>
  </table>';

//  echo $html;
  $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
  $pdf->SetTitle('informazioni apprendisti');

  $pdf->setHeaderData("",0,"informazioni apprendisti","");
  // set header and footer fonts
  $pdf->setHeaderFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

  // set default monospaced font
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

  // set margins
  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
  $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

  // set auto page breaks
  $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

  // set image scale factor
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


  // ---------------------------------------------------------

  // set font
  $pdf->SetFont('dejavusans', '', 10);

  // add a page
  $pdf->AddPage();
  // output the HTML content
  $pdf->writeHTML($html, true, false, true, false, '');
  $pdf->Output('apprendisti.pdf', 'I');
  }
else{
  echo "<script>location.href='apprendisti.php'</script>";
}
?>
