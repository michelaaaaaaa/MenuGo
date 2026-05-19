<?php
require 'config.php'; need_login();
function pdf_text($text){ return str_replace(['\\','(',')'], ['\\\\','\\(','\\)'], $text); }
$rows=[];
$q=mysqli_query($conn,"SELECT p.*, SUM(d.qty) qty FROM pesanan p JOIN detail_pesanan d ON p.id_pesanan=d.id_pesanan GROUP BY p.id_pesanan ORDER BY p.tanggal DESC");
while($p=mysqli_fetch_assoc($q)){
  $rows[]=[ $p['kode_pesanan'], date('d/m/Y H:i',strtotime($p['tanggal'])), substr(order_items($p['id_pesanan']),0,40), $p['qty'], $p['metode_bayar'], rupiah($p['total']) ];
}
$pendapatan=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COALESCE(SUM(total),0) t FROM pesanan WHERE status_pesanan!='Dibatalkan'"))['t'];
$content="BT /F1 18 Tf 50 800 Td (".pdf_text('Laporan Penjualan MenuGo').") Tj ET\n";
$content.="BT /F1 11 Tf 50 775 Td (".pdf_text('Tanggal Export: '.date('d/m/Y H:i')).") Tj ET\n";
$content.="BT /F1 11 Tf 50 758 Td (".pdf_text('Total Pendapatan: '.rupiah($pendapatan)).") Tj ET\n";
$y=730;
$headers=['ID','Waktu','Menu','Qty','Bayar','Total']; $x=[50,115,205,410,455,510];
foreach($headers as $i=>$h){ $content.="BT /F1 10 Tf {$x[$i]} $y Td (".pdf_text($h).") Tj ET\n"; }
$y-=18;
foreach($rows as $r){
  if($y<50) break;
  foreach($r as $i=>$v){ $content.="BT /F1 9 Tf {$x[$i]} $y Td (".pdf_text((string)$v).") Tj ET\n"; }
  $y-=17;
}
$objects=[];
$objects[]="1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj";
$objects[]="2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj";
$objects[]="3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj";
$objects[]="4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj";
$objects[]="5 0 obj << /Length ".strlen($content)." >> stream\n$content\nendstream endobj";
$pdf="%PDF-1.4\n"; $offsets=[0];
foreach($objects as $obj){ $offsets[]=strlen($pdf); $pdf.=$obj."\n"; }
$xref=strlen($pdf); $pdf.="xref\n0 ".(count($objects)+1)."\n0000000000 65535 f \n";
for($i=1;$i<=count($objects);$i++){ $pdf.=sprintf('%010d 00000 n ', $offsets[$i])."\n"; }
$pdf.="trailer << /Size ".(count($objects)+1)." /Root 1 0 R >>\nstartxref\n$xref\n%%EOF";
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="laporan_penjualan_menugo.pdf"');
echo $pdf;
?>
