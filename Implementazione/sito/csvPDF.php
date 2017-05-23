<?php
session_start();
if(isset($_SESSION["email"]) && ($_SESSION["tipo"]=="master" OR $_SESSION["tipo"]=="admin") AND isset($_POST["filecsv"])){
  // creare un pdf A3 contenente tutti i dati
  require_once('tcpdf/tcpdf.php'); // libreria per la creazione del pdf
  $file = "uploads/".$_POST["filecsv"];
  $html="";
  if (file_exists($file)) {
    // creazione del th (usare td e b poiché th non funziona corretamente)
    $html .= '<table border="1" cellpadding="2"><tr>';
    $html .= '<td><b>Apprendista</b></td>';
    $html .= '<td><b>Data di nascita</b></td>';
    $html .= '<td><b>Sede</b></td>';
    $html .= '<td><b>Indirizzo</b></td>';
    $html .= '<td><b>Domicilio</b></td>';
    $html .= '<td><b>Telefono</b></td>';
    $html .= '<td><b>Datore</b></td>';
    $html .= '<td><b>Indirizzo datore</b></td>';
    $html .= '<td><b>Domicilio datore</b></td>';
    $html .= '<td><b>Telefono datore</b></td>';
    $html .= '<td><b>Data di inizio contratto</b></td>';
    $html .= '<td><b>Anno di fine contratto</b></td>';
    $html .= '<td><b>Anno scolastico apprendista</b></td>';
    $html .= '<td><b>Professione contratto</b></td>';
    $html .= '<td><b>Numero contratto</b></td>';
    $html .= '<td><b>Statuto contratto</b></td>';
    $html .= '<td><b>Rappresentante legale</b></td>';
    $html .= '<td><b>Formatore</b></td>';
    $html .= '<td><b>Email formatore</b></td>';
    $html .= '</tr>';
    // inserimento dati
    $handle = fopen($file,"r");
    $data = null;
    fgets($handle);
    // lettura del file csv
    while (($getData = fgetcsv($handle,10000,";")) != FALSE){
      // creazione delle variabili
      $apprendista = ucwords(strtolower(utf8_encode($getData[0]))); // cognome nome apprendista
      $nascitaApprendista = $getData[1]; // data di nascita apprendista
      $dum = explode('.', $nascitaApprendista);
      $nascitaApprendista = $dum[2].'-'.$dum[1].'-'.$dum[0]; //  formattato per db
      $sede = ucwords(strtolower(utf8_encode($getData[2]))); // sede scolastica e località
      $indirizzoApprendista = ucwords(strtolower(utf8_encode($getData[3]))); // indirizzo apprendista
      $domicilioApprendista = ucwords(strtolower(utf8_encode($getData[4]))); // domicilio apprendista
      $telefonoApprendista = $getData[5]; // Tel. priv. allievo
      $datore = ucwords(strtolower(utf8_encode($getData[6]))); // Nome datore lavoro
      $indirizzoDatore = ucwords(strtolower(utf8_encode($getData[7]))); // Indirizzo postale datore lavoro
      $domicilioDatore = ucwords(strtolower(utf8_encode($getData[8]))); // CAP - Località postale datore lavoro
      $telefonoDatore = $getData[9]; // Datore lavoro tel professione
      $fineContratto = $getData[10]; // Anno fine contratto
      $inizioContratto = $getData[11]; // Data inizio contratto professione
      $dum = explode('.', $inizioContratto);
      $inizioContratto = $dum[2].'-'.$dum[1].'-'.$dum[0]; //  formattato per db
      $annoScolastico = $getData[12]; // Anno scolastico apprendista
      $dum = explode(' ', $annoScolastico);
      $annoScolastico = $dum[0]; //  formattato per db
      $professione =strtolower(utf8_encode($getData[13])); // Professione apprendista
      $contratto = $getData[14]; // Numero contratto
      if(strlen($contratto)!=9){ // se manca lo 0 finale
        $contratto .="0";
      }
      $statuto = $getData[15]; // Statuto contratto
      $rappresentante = ucwords(strtolower(utf8_encode($getData[16]))); // Cognome nome rappresentante
      $formatore = ucwords(strtolower(utf8_encode($getData[17]))); // formatore
      $emailFormatore = strtolower($getData[18]); // Datore lavoro email

      // inserimento dei dati
      $html .= '<tr>';
      $html .= '<td>'.$apprendista.'</td>';
      $html .= '<td>'.$nascitaApprendista.'</td>';
      $html .= '<td>'.$sede.'</td>';
      $html .= '<td>'.$indirizzoApprendista.'</td>';
      $html .= '<td>'.$domicilioApprendista.'</td>';
      $html .= '<td>'.$telefonoApprendista.'</td>';
      $html .= '<td>'.$datore.'</td>';
      $html .= '<td>'.$indirizzoDatore.'</td>';
      $html .= '<td>'.$domicilioDatore.'</td>';
      $html .= '<td>'.$telefonoDatore.'</td>';
      $html .= '<td>'.$inizioContratto.'</td>';
      $html .= '<td>'.$fineContratto.'</td>';
      $html .= '<td>'.$annoScolastico.'</td>';
      $html .= '<td>'.$professione.'</td>';
      $html .= '<td>'.$contratto.'</td>';
      $html .= '<td>'.$statuto.'</td>';
      $html .= '<td>'.$rappresentante.'</td>';
      $html .= '<td>'.$formatore.'</td>';
      $html .= '<td>'.$emailFormatore.'</td>';
      $html .= '</tr>';
    }
    $html .='</table>';
    //echo $html;
    $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      // set document information
    $pdf->SetTitle('informazioni formatori');

    $pdf->setHeaderData("",0,"file csv","");
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
    $pdf->SetFont('dejavusans', '', 8);

    // add a page
    $pdf->AddPage('L','A3');
    // output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('filecsv.pdf', 'I');
  }
  else{
    echo "Non esiste";
  }
}
else{
  echo "<script>location.href='gestioneCSV.php'</script>";
}
?>
