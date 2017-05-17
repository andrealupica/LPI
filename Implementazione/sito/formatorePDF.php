<?php
session_start();
if(isset($_SESSION["email"]) AND isset($_POST["pdf"])){
  include_once "connection.php";
  require_once('tcpdf/tcpdf.php'); // libreria per la creazione del pdf

  $html = '<table border="1" cellpadding="5"><thead><tr>';
  $html .= '<th><b>Datore:</b></th>';
  $html .= '<th><b>Nome e cognome HR:</b></th>';
  $html .= '<th><b>Email HR:</b></th>';
  $html .= '<th><b>Telefono HR:</b></th>';
  $html .= '</tr>';
  $html .= '<tr><td>'.$_POST["insertDatore"].'</td>';
  $html .= '<td>'.$_POST["insertHR"].'</td>';
  $html .= '<td>'.$_POST["insertEmailHR"].'</td>';
  $html .= '<td>'.$_POST["insertTelefonoHR"].'</td></tr></table>';
  $html .= '<table  border="1 " cellpadding="5"><tr>';
  $html .= '<th><b>Nome e cognome Formatore:</b></th>';
  $html .= '<th><b>Email:</b></th>';
  $html .= '<th><b>Telefono:</b></th></tr><tr>';
  $html .= '<td>'.$_POST["insertNome"].'</td>';
  $html .= '<td>'.$_POST["insertEmail"].'</td>';
  $html .= '<td>'.$_POST["insertTelefono"].'</td></tr>';
  $html .= '<tr><th><b>osservazioni:</b></th>';
  $html .= '<td colspan="2">'.$_POST["osservazioni"].'</td>';
  $html .= '</tr></table>';

//  echo $html;
  $pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
  $pdf->SetTitle('informazioni formatore');

  $pdf->setHeaderData("",0,"informazioni formatore","");
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
  $pdf->Output('formatore.pdf', 'I');
  }
else{
  echo "<script>location.href='formatori.php'</script>";
}
?>
