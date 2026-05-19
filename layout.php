<?php
function render_header($title,$page){
  $search = in_array($page,['menu','kategori','pesanan']);
  $pdf = in_array($page,['laporan']);
  echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>MenuGo - $title</title>";
  echo <<<'HTML'
<style>
@import url('https://fonts.googleapis.com/css2?family=Boogaloo&family=Inter:wght@400;500;600;700;800&family=Nunito:wght@600;700;800&display=swap');
  :root{--bg:#A2D1B1;--cream:#F3E8CC;--brand:#F86015;--mint:#5CB7A5;--green:#023820;--darkgreen:#19532B;--red:#EE3F24;--orange:#EEAB43;--blue:#3778EB;--text:#0e0e0e;--muted:#8A8A8A;}
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:Inter,Arial,sans-serif;background:var(--bg);color:var(--green);min-height:100vh;overflow-x:hidden}.app{display:flex;min-height:100vh;background:var(--bg)}
  .sidebar{width:300px;background:#fff;border-radius:0 8px 8px 0;display:flex;flex-direction:column;position:fixed;left:0;top:0;bottom:0;z-index:5}.logo{font-family:Boogaloo,cursive;font-size:70px;line-height:.95;color:var(--brand);padding:25px 12px 18px;text-decoration:underline;text-decoration-color:var(--mint);text-decoration-thickness:5px;text-underline-offset:12px}.nav{padding:0 12px;flex:1}.nav a{display:flex;align-items:center;gap:12px;text-decoration:none;color:#050505;font-family:Nunito,Inter,sans-serif;font-size:24px;font-weight:800;padding:8px 10px;border-radius:10px;margin:19px 0;height:50px}.nav a.active{background:var(--mint);color:#fff}.nav span{width:25px;text-align:center;font-size:25px;line-height:1}.userbox{height:76px;border-top:3px solid rgba(68,68,68,.45);display:flex;align-items:center;gap:14px;padding:10px 14px 10px 28px}.avatar{width:42px;height:42px;border-radius:50%;background:#B8E7C6;display:flex;align-items:center;justify-content:center;font-size:23px;color:#111}.uname{font-family:Nunito,Inter,sans-serif;font-weight:800;font-size:20px;color:#000;line-height:1.1}.role{font-family:Nunito,Inter,sans-serif;color:#666;font-weight:800;font-size:17px}.logout{margin-left:auto;background:#C00F0C;color:white;text-decoration:none;padding:7px 22px;border-radius:18px;font-weight:800;line-height:1}.main{margin-left:310px;flex:1;background:var(--cream);border-radius:12px 0 12px 12px;min-height:100vh}.topbar{height:88px;background:#fff;border-radius:0 0 10px 10px;box-shadow:0 2px 0 #BFBEBE;display:flex;align-items:center;justify-content:space-between;padding:0 26px}.title{font-family:Boogaloo,cursive;font-size:48px;line-height:1;color:var(--mint)}.search{border:2px solid #ddd;border-radius:14px;padding:10px 16px;font-size:16px;width:210px;background:#fff;color:#555}.content{padding:19px 24px 42px}.cards{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:40px;filter:drop-shadow(4px 4px 4px rgba(0,0,0,.2))}.card{position:relative;background:white;border-radius:12px;min-height:122px;display:flex;flex-direction:column;align-items:center;justify-content:center}.card:before{content:'';position:absolute;left:0;top:0;bottom:0;width:10px;background:#E86F44;border-radius:12px 0 0 12px}.card:nth-child(2):before{background:var(--orange)}.card:nth-child(3):before{background:#A2D1B1}.card:nth-child(4):before{background:var(--mint)}.num{font-family:Boogaloo,cursive;font-size:40px;line-height:1.1;color:#000;text-align:center}.label{font-family:Nunito,Inter,sans-serif;font-size:20px;color:var(--muted);font-weight:800;text-align:center}.trend{font-family:Nunito,Inter,sans-serif;font-weight:800;color:#22C55E;margin-top:9px;font-size:15px}.orange{color:#f39a0a}.blue{color:#06B6D4}.section-title{font-family:Boogaloo,cursive;font-size:36px;line-height:1.1;color:#000;margin:22px 0 14px}.panel{background:white;border-radius:12px;padding:14px 16px}.panel h2{font-family:Nunito,Inter,sans-serif;font-size:20px;line-height:27px;color:#000;text-align:left;margin-bottom:8px}.panel.chart h2{text-align:center}.grid2{display:grid;grid-template-columns:1.25fr 1fr;gap:24px;margin-bottom:24px}.bars{height:150px;display:flex;align-items:flex-end;gap:6px;justify-content:center;padding-top:4px}.barwrap{width:54px;text-align:center;font-family:Nunito,Inter,sans-serif;font-size:12px;font-weight:800;color:#000}.bar{border-radius:7px 7px 0 0;color:white;font-size:10px;font-weight:800;display:flex;align-items:center;justify-content:center;min-height:8px}.bar span{display:block;padding:2px}.barlabel{height:16px;margin-top:2px}.red{background:#EE3F24}.green{background:#19532B}.orangebg{background:#F86015}.bluebg{background:#3778EB}.lineitem{margin:9px 0;color:#777;font-size:12px;font-family:Nunito,Inter,sans-serif;font-weight:800}.lineitem:after{content:'';display:block;clear:both}.line{height:3px;margin-top:7px;background:#FF0000;border-radius:999px}.line.green{background:#19532B}.line.orangebg{background:#F86015}.line.bluebg{background:#3778EB}.table{width:100%;border-collapse:collapse;background:white;border-radius:14px;overflow:hidden}.table th,.table td{padding:13px 16px;border-bottom:1px solid #eee;text-align:left;font-size:13px}.table th{font-family:Nunito,Inter,sans-serif;color:#aaa;text-transform:uppercase;letter-spacing:.8px;font-weight:800}.table td{color:#555}.table b,.price{color:var(--green);font-weight:800}.code{color:var(--red)!important;font-weight:800}.badge{display:inline-block;padding:5px 10px;border-radius:20px;font-weight:800;font-size:13px}.diterima{background:rgba(174,231,195,.5);color:#22C55E}.diproses{background:rgba(249,161,27,.12);color:#B07000}.selesai{background:#eee;color:#888}.dibatalkan{background:#ffe2dd;color:#ff321d}.tersedia{background:rgba(174,231,195,.5);color:#22C55E}.habis{background:#ffe2dd;color:#ff321d}.btn{display:inline-block;border:0;border-radius:8px;padding:9px 16px;text-decoration:none;color:var(--green);background:#F7CB82;font-family:Nunito,Inter,sans-serif;font-weight:800;cursor:pointer}.btn-primary{background:#EE6D45;color:#fff}.btn-green{background:rgba(92,183,165,.35);color:#5CB7A5}.btn-red{background:#C00F0C;color:#fff}.btn-small{padding:6px 12px;font-size:13px}.right{display:flex;gap:10px;justify-content:flex-end;align-items:center}.formbox{background:#fff;border-radius:0 0 12px 12px;padding:26px 38px;max-width:820px;margin:0 auto}.formgroup{margin-bottom:20px}.formgroup label{display:block;font-family:Nunito,Inter,sans-serif;font-size:24px;font-weight:800;color:#000;margin-bottom:8px}.input,select,textarea{width:100%;border:2px solid #aaa;border-radius:10px;padding:12px 18px;font-size:22px;font-family:Nunito,Inter,sans-serif;font-weight:800;background:#fafafa;color:#111}textarea{height:104px;resize:vertical}.actions{display:flex;gap:56px;justify-content:center;margin-top:34px}.actions .btn{font-size:24px;min-width:240px;text-align:center;padding:14px 22px}.msg{background:#d1fae5;color:#065f46;padding:12px 16px;border-radius:10px;margin-bottom:16px;font-weight:800}.danger{background:#fee2e2;color:#991b1b}.photo-box{border:2px solid #aaa;border-radius:12px;height:236px;display:flex;align-items:center;justify-content:center;flex-direction:column;color:#5ebba8;font-weight:800;font-size:68px;background:#fafafa;overflow:hidden}.photo-box .btn{font-size:20px;margin-top:8px}.upload-box{cursor:pointer}.upload-box small{font-size:14px;color:#888;margin-top:8px;font-family:Nunito,Inter,sans-serif}.file-input{display:none}.photo-preview{width:200px;height:150px;object-fit:cover;border-radius:12px;display:block}.menu-photo-thumb{width:77px;height:69px;object-fit:cover;border-radius:12px;display:block}.upload-icon{line-height:1}.category-bars{display:flex;align-items:flex-end;gap:8px;height:140px;margin-top:25px}.category-bars div{width:54px;border-radius:8px 8px 0 0}.legend{margin-top:30px;line-height:2;color:var(--green);font-family:Nunito,Inter,sans-serif;font-size:13px}.dot{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:7px}.menu-img{width:77px;height:69px;border-radius:12px;background:linear-gradient(135deg,#F8ECBC,#EEAB43);display:flex;align-items:center;justify-content:center;font-size:32px}.menu-table th:nth-child(1),.menu-table td:nth-child(1){width:120px}.menu-table th:nth-child(3),.menu-table td:nth-child(3){width:280px}.top-actions{display:flex;gap:10px;align-items:center}select.status-select{font-size:13px;padding:7px 26px 7px 14px;border:1px solid rgba(0,0,0,.1);border-radius:8px;color:var(--green);background:#fff;min-width:140px}@media(max-width:900px){.sidebar{position:static;width:100%;min-height:auto}.app{display:block}.main{margin-left:0;border-radius:0}.cards,.grid2{grid-template-columns:1fr;filter:none}.topbar{height:auto;padding:15px;gap:12px}.title{font-size:38px}.content{padding:15px}.table{font-size:12px;display:block;overflow-x:auto}.nav a{font-size:20px}.logo{font-size:52px}.actions{flex-direction:column;gap:12px}.actions .btn{min-width:0}.search{width:160px}}
</style>
HTML;
  echo "
  </head>
  <body>
  <div class='app'>
  <aside class='sidebar'>
  <div class='logo'>MenuGo</div>
  <nav class='nav'>";
  $items=[
    ['dashboard.php','dashboard','Dashboard','▦'],
    ['menu.php','menu','Menu','☰'],
    ['kategori.php','kategori','Kategori','◇'],
    ['pesanan.php','pesanan','Pesanan','▣'],
    ['laporan.php','laporan','Penjualan','▥']
  ];

  foreach($items as $it){
    $cls=active($it[1],$page);
    echo "
    <a class='$cls' href='{$it[0]}'>
      <span>{$it[3]}</span>{$it[2]}
    </a>";}
  
  $nama=$_SESSION['admin']['nama'] ?? 'Boni';
  echo "</nav><div class='userbox'><div class='avatar'>☺</div><div><div class='uname'>$nama</div><div class='role'>Admin</div></div><a class='logout' href='logout.php'>Keluar</a></div></aside><main class='main'>
  <div class='topbar'>
    <div class='title'>$title</div>";  
  if($search){ 
    echo "
      <form method='get'>
        <input class='search' name='q' placeholder='🔍  Cari...' value='".htmlspecialchars($_GET['q'] ?? '')."'>
      </form>"; 
    }
    
  if($pdf){
    // Hapus style='margin-bottom:14px' karena flexbox topbar sudah mengatur posisinya agar rapi
    echo "<div class='right'><a class='btn btn-primary' href='export_laporan_pdf.php' target='_blank'>Export Laporan PDF</a></div>";
  }
  
  // Penutup div topbar dan pembuka div content dipindah ke paling bawah
  echo "</div><div class='content'>";
}
function render_footer(){ echo "</div></main></div></body></html>"; }
?>