<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'menugo_db';
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) { die('Koneksi database gagal: ' . mysqli_connect_error()); }
function rupiah($angka){ return 'Rp ' . number_format((int)$angka,0,',','.'); }
function esc($v){ global $conn; return mysqli_real_escape_string($conn, trim($v)); }
function upload_menu_photo($field='foto',$old=''){
  if(!isset($_FILES[$field]) || $_FILES[$field]['error']===UPLOAD_ERR_NO_FILE){ return $old; }
  if($_FILES[$field]['error']!==UPLOAD_ERR_OK){ return $old; }

  $maxSize = 2 * 1024 * 1024; // maksimal 2MB
  if($_FILES[$field]['size'] > $maxSize){ die('Ukuran gambar maksimal 2MB.'); }

  $tmp = $_FILES[$field]['tmp_name'];
  $info = @getimagesize($tmp);
  if($info === false){ die('File harus berupa gambar.'); }

  $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
  $mime = $info['mime'];
  if(!isset($allowed[$mime])){ die('Format gambar harus JPG, PNG, WEBP, atau GIF.'); }

  $dir = __DIR__ . '/uploads/menu';
  if(!is_dir($dir)){ mkdir($dir, 0777, true); }

  $name = 'menu_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
  $dest = $dir . '/' . $name;
  if(!move_uploaded_file($tmp, $dest)){ die('Gagal mengupload gambar.'); }

  if($old && file_exists(__DIR__ . '/' . $old)){ @unlink(__DIR__ . '/' . $old); }
  return 'uploads/menu/' . $name;
}
function need_login(){ if(!isset($_SESSION['admin'])){ header('Location: index.php'); exit; } }
function active($page,$cur){ return $page===$cur ? 'active' : ''; }

function latest_order_date(){
  global $conn;
  $r = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COALESCE(MAX(DATE(tanggal)), CURDATE()) d FROM pesanan"));
  return $r['d'] ?: date('Y-m-d');
}
function day_label_id($date){
  $map=['Sun'=>'Min','Mon'=>'Sen','Tue'=>'Sel','Wed'=>'Rab','Thu'=>'Kam','Fri'=>'Jum','Sat'=>'Sab'];
  return $map[date('D',strtotime($date))] ?? date('D',strtotime($date));
}
function sales_last_days($days=7){
  global $conn;
  $end = latest_order_date();
  $start = date('Y-m-d', strtotime($end.' -'.($days-1).' days'));
  $sql = "SELECT DATE(tanggal) d, COALESCE(SUM(total),0) revenue, COUNT(*) orders
          FROM pesanan
          WHERE status_pesanan!='Dibatalkan' AND DATE(tanggal) BETWEEN '$start' AND '$end'
          GROUP BY DATE(tanggal)";
  $res = mysqli_query($conn,$sql);
  $map=[];
  while($r=mysqli_fetch_assoc($res)){ $map[$r['d']]=$r; }
  $out=[];
  for($i=0;$i<$days;$i++){
    $d=date('Y-m-d', strtotime($start." +$i days"));
    $out[]=[
      'date'=>$d,
      'label'=>day_label_id($d),
      'revenue'=>(int)($map[$d]['revenue'] ?? 0),
      'orders'=>(int)($map[$d]['orders'] ?? 0)
    ];
  }
  return $out;
}
function compact_rupiah($n){
  $n=(int)$n;
  if($n>=1000000){ return str_replace('.',',',rtrim(rtrim(number_format($n/1000000,1,',','.'),'0'),',')) . 'jt'; }
  if($n>=1000){ return str_replace('.',',',rtrim(rtrim(number_format($n/1000,1,',','.'),'0'),',')) . 'rb'; }
  return (string)$n;
}
function render_bars($rows,$valueKey='revenue'){
  $max=0; foreach($rows as $r){ if($r[$valueKey]>$max) $max=$r[$valueKey]; }
  if($max<=0) $max=1;
  $colors=['red','green','orangebg'];
  $html=''; $i=0;
  foreach($rows as $r){
    $h=max(12, round(($r[$valueKey]/$max)*125));
    if($r[$valueKey]==0) $h=8;
    $cls=$colors[$i++%3];
    $val=$valueKey==='revenue' ? compact_rupiah($r[$valueKey]) : (int)$r[$valueKey];
    $html.="<div class='barwrap'><div class='bar $cls' style='height:{$h}px'><span>$val</span></div><div class='barlabel'>{$r['label']}</div></div>";
  }
  return $html;
}
function calc_percent_change($current,$previous){
  $current=(int)$current; $previous=(int)$previous;
  if($previous<=0){ return $current>0 ? '+100%' : '0%'; }
  $pct=round((($current-$previous)/$previous)*100);
  return ($pct>=0?'+':'').$pct.'%';
}

function order_items($id){
  global $conn;
  $q=mysqli_query($conn,"SELECT m.nama_menu,d.qty FROM detail_pesanan d JOIN menu m ON d.id_menu=m.id_menu WHERE d.id_pesanan=".(int)$id);
  $arr=[]; while($r=mysqli_fetch_assoc($q)){ $arr[]=$r['nama_menu'].' x'.$r['qty']; }
  return implode(', ',$arr);
}
?>
