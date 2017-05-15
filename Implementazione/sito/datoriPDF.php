<?php
session_start();
if(isset($_SESSION["email"]) AND isset($_POST["pdf"])){
  include_once "connection.php";
  require_once('tcpdf/tcpdf.php'); // libreria per la creazione del pdf

  $colonne = "";
  $where = "";
  // se è checckato il nome
  if(isset($_POST["nome"])){
    $colonne.="dat_nome AS 'datore',";
  }
  // se è checckato l indirizzo
  if(isset($_POST["indirizzo"])){
    $colonne.="dat_indirizzo AS 'indirizzo',";
  }
  // se è checckato il domicilio
  if(isset($_POST["domicilio"])){
    $colonne.="dat_domicilio AS 'domicilio',";
  }
  // se è checckato lo statuto
  if(isset($_POST["telefono"])){
    $colonne.="dat_telefono AS 'telefono',";
  }
  // se è checckato il nome HR
  if(isset($_POST["nomeHR"])){
    $colonne.="dat_nomeHR AS 'HR',";
  }
  // se è checckato l email HR
  if(isset($_POST["emailHR"])){
    $colonne.="dat_emailHR AS 'emailHR',";
  }
  // se è checckato il nome HR
  if(isset($_POST["telefonoHR"])){
    $colonne.="dat_telefonoHR AS 'telefonoHR',";
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
      $where .="(dat_nome LIKE '%".$option[$i]."%' OR dat_indirizzo LIKE '%".$option[$i]."%' OR dat_indirizzo LIKE '%".$option[$i]."%' OR
      dat_telefono LIKE '%".$option[$i]."%' OR dat_nomeHR LIKE '%".$option[$i]."%' OR
      dat_emailHR LIKE '%".$option[$i]."%' OR dat_telefonoHR LIKE '%".$option[$i]."%')";
      $where.=" AND ";
    }
  }
  //echo $where."<br>";
  $completeQuery = "SELECT ".$colonne." FROM datore"
  .$where." dat_flag=1 order by dat_nome";

  //echo $completeQuery."<br>";

  $query = $conn->prepare($completeQuery);
  $query->execute();


  $html = '
  <table border="1" cellpadding="5">
    <thead>
      <tr>';
  if(isset($_POST["nome"])){
    $html .= '<th>Datore:</th>';
  }
  if(isset($_POST["indirizzo"])){
    $html .= '<th>Indirizzo:</th>';
  }
  if(isset($_POST["domicilio"])){
    $html .= '<th>Domicilio:</th>';
  }
  if(isset($_POST["telefono"])){
    $html .= '<th>Telefono:</th>';
  }
  if(isset($_POST["nomeHR"])){
    $html .= '<th>Nome HR:</th>';
  }
  if(isset($_POST["emailHR"])){
    $html .= '<th>Email HR:</th>';
  }
  if(isset($_POST["telefonoHR"])){
    $html .= '<th>Telefono HR:</th>';
  }


  $html.="</tr>";
  while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html.="<tr>";
    if(isset($_POST["nome"])){
      $html.="<td>".$row['datore']."</td>";
    }
    if(isset($_POST["indirizzo"])){
      $html.="<td>".$row['indirizzo']."</td>";
    }
    if(isset($_POST["domicilio"])){
      $html.="<td>".$row['domicilio']."</td>";
    }
    if(isset($_POST["telefono"])){
      $html.="<td>".$row['telefono']."</td>";
    }
    if(isset($_POST["nomeHR"])){
      $html.="<td>".$row['HR']."</td>";
    }
    if(isset($_POST["emailHR"])){
      $html.="<td>".$row['emailHR']."</td>";
    }
    if(isset($_POST["telefonoHR"])){
      $html.="<td>".$row['telefonoHR']."</td>";
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
