<?php
session_start();
if(isset($_SESSION["email"]) AND isset($_POST["pdf"])){
  include_once "connection.php";
  require_once('tcpdf/tcpdf.php'); // libreria per la creazione del pdf

  // inserimento della tabella + dati nella stringa
  $html = '<table border="1" cellpadding="5"><tr>';
  $html .= '<td><b>Contratto:</b></td>';
  $html .= '<td><b>Apprendista:</b></td>';
  $html .= '<td><b>Telefono:</b></td>';
  $html .= '<td><b>Data di nascita:</b></td>';
  $html .= '</tr>';
  $html .= '<tr><td>'.$_POST["insertContratto"].'</td>';
  $html .= '<td>'.$_POST["insertNome"].'</td>';
  $html .= '<td>'.$_POST["insertTelefono"].'</td>';
  $html .= '<td>'.$_POST["insertNascita"].'</td>';
  $html .= '</tr>';
  $html .= '<tr><td><b>Statuto contratto:</b></td>';
  $html .= '<td><b>Rappresentante:</b></td>';
  $html .= '<td><b>Indirizzo:</b></td>';
  $html .= '<td><b>Domicilio:</b></td>';
  $html .= '</tr>';
  $html .= '<tr><td>'.$_POST["insertStatuto"].'</td>';
  $html .= '<td>'.$_POST["insertRappresentante"].'</td>';
  $html .= '<td>'.$_POST["insertIndirizzo"].'</td>';
  $html .= '<td>'.$_POST["insertDomicilio"].'</td>';
  $html .= '</tr>';
  $html .= '<tr><td><b>Professione:</b></td>';
  $html .= '<td><b>Data di inizio:</b></td>';
  $html .= '<td><b>Anno di fine:</b></td>';
  $html .= '<td><b>Anno scolastico:</b></td>';
  $html .= '</tr>';
  $html .= '<tr><td>'.$_POST["insertProfessione"].'</td>';
  $html .= '<td>'.$_POST["insertInizio"].'</td>';
  $html .= '<td>'.$_POST["insertFine"].'</td>';
  $html .= '<td>'.$_POST["insertScolastico"].'</td>';
  $html .= '</tr>';
  $html .= '<tr><td><b>Sede:</b></td>';
  $html .= '<td colspan="3">'.$_POST["insertSede"].'</td>';
  $html .= '</tr>';
  $html .= '<tr><td><b>Osservazioni:</b></td>';
  $html .= '<td colspan="3">'.$_POST["osservazioni"].'</td>';
  $html .= '</tr>';
  $html .= '</table><br><table border="1" cellpadding="5">';
  $html .= '<tr><td><b>Datore:</b></td>';
  $html .= '<td><b>Indirizzo datore:</b></td>';
  $html .= '<td><b>Domicilio datore:</b></td>';
  $html .= '</tr>';
  $html .= '<tr><td>'.$_POST["insertDatore"].'</td>';
  $html .= '<td>'.$_POST["insertDatoreIndirizzo"].'</td>';
  $html .= '<td>'.$_POST["insertDatoreDomicilio"].'</td>';
  $html .= '</tr>';
  $html .= '<tr><td><b>Datore:</b></td>';
  $html .= '<td><b>Indirizzo datore:</b></td>';
  $html .= '<td><b>Domicilio datore:</b></td>';
  $html .= '</tr>';
  $html .= '<tr><td>'.$_POST["insertDatoreTelefono"].'</td>';
  $html .= '<td>'.$_POST["insertFormatore"].'</td>';
  $html .= '<td>'.$_POST["insertEmailFormatore"].'</td>';
  $html .= '</tr>';
  $html .= '<tr><td><b>osservazioni:</b></td>';
  $html .= '<td colspan="2">'.$_POST["osservazioniFormatore"].'</td>';
  $html .= '</tr>';
  $html .= '</table>';

//  echo $html;
  $pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
  $pdf->SetTitle('informazioni apprendista');

  $pdf->setHeaderData("",0,"informazioni apprendista","");
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
  $pdf->Output('apprendista.pdf', 'I');
  }
else{
  echo "<script>location.href='apprendisti.php'</script>";
}
?>
