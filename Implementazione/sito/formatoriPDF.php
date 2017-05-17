<?php
session_start();
if(isset($_SESSION["email"]) AND isset($_POST["pdf"])){
  include_once "connection.php";
  require_once('tcpdf/tcpdf.php'); // libreria per la creazione del pdf

  $gruppo = $_POST["gruppo"];
  $colonne = "";
  $where = "";
  // se è checckato il nome
  if(isset($_POST["nome"])){
    $colonne.="form.for_nome AS 'formatore',";
  }
  // se è checckato il telefono
  if(isset($_POST["telefono"])){
    $colonne.="form.for_telefono AS 'telefonos',";
  }
  // se è checckato l'email
  if(isset($_POST["email"])){
    $colonne.="form.for_email AS 'email',";
  }
  // se è checckato il datore
  if(isset($_POST["datore"])){
    $colonne.="dat.dat_nome AS 'datore',";
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
      $where .="(form.for_nome LIKE '%".$option[$i]."%' OR form.form_email LIKE '%".$option[$i]."%'
      OR form.form_telefono LIKE '%".$option[$i]."%' OR dat.dat_nome LIKE '%".$option[$i]."%')";
      $where.=" AND ";
    }
  }
  //echo $where."<br>";
  $completeQuery = "SELECT ".$colonne." FROM formatore form
  JOIN datore dat ON dat.dat_id = form.dat_id"
  .$where." form.for_flag=1 order by form.for_nome";

  //echo $completeQuery."<br>";

  $query = $conn->prepare($completeQuery);
  $query->execute();


  $html = '
  <table border="1" cellpadding="5">
    <thead>
      <tr>';
  if(isset($_POST["nome"])){
    $html .= '<th><b>Formatore:</b></th>';
  }
  if(isset($_POST["telefono"])){
    $html .= '<th><b>Telefono:</b></th>';
  }
  if(isset($_POST["email"])){
    $html .= '<th><b>Email:</b></th>';
  }
  if(isset($_POST["datore"])){
    $html .= '<th><b>Datore:</b></th>';
  }

    $html.="</tr>";
  while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $html.="<tr>";
    if(isset($_POST["nome"])){
      $html.="<td>".$row['formatore']."</td>";
    }
    if(isset($_POST["telefono"])){
      $html.="<td>".$row['telefono']."</td>";
    }
    if(isset($_POST["email"])){
      $html.="<td>".$row['email']."</td>";
    }
    if(isset($_POST["datore"])){
      $html.="<td>".$row['datore']."</td>";
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
  $pdf->SetTitle('informazioni formatori');

  $pdf->setHeaderData("",0,"informazioni formatori","");
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
  $pdf->Output('formatori.pdf', 'I');
  }
else{
  echo "<script>location.href='formatori.php'</script>";
}
?>
