<?php require 'config.php';
if(isset($_SESSION['admin'])){ header('Location: dashboard.php'); exit; }
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $u=esc($_POST['username'] ?? ''); $p=esc($_POST['password'] ?? '');
  $q=mysqli_query($conn,"SELECT * FROM admin WHERE username='$u' AND password='$p' LIMIT 1");
  if(mysqli_num_rows($q)>0){ $_SESSION['admin']=mysqli_fetch_assoc($q); header('Location: dashboard.php'); exit; }
  else $error='Username atau password salah.';
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Login MenuGo</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Chewy&family=Inter:wght@400;600;700;800&display=swap');
*{box-sizing:border-box}body{margin:0;min-height:100vh;background:#fff3bd;display:flex;align-items:center;justify-content:center;font-family:Inter,Arial,sans-serif}.box{width:356px;background:#fff;border-radius:48px;padding:28px 26px 38px;box-shadow:7px 7px 8px rgba(0,0,0,.28)}.logo{font-family:Chewy,cursive;color:#fa5418;font-size:62px;line-height:.9}.subtitle{font-size:24px;color:#5ebba8;font-weight:800;margin:12px 0 35px}label{display:block;margin:16px 0 6px;color:#444;font-weight:600}.input{width:100%;height:44px;border:1.7px solid #ddd;border-radius:9px;padding:8px 12px;font-size:16px}.btn{width:100%;height:44px;border:0;border-radius:8px;background:#5ebba8;color:white;font-weight:800;margin-top:18px;font-size:15px}.err{background:#fee2e2;color:#991b1b;padding:10px;border-radius:8px;margin-top:12px;font-weight:700}
</style></head><body><form class="box" method="post"><div class="logo">MenuGo</div><div class="subtitle">Login Ke Dashboard</div><?php if($error) echo '<div class="err">'.$error.'</div>'; ?><label>Email atau Username</label><input class="input" name="username" value="admin"><label>Password</label><input class="input" type="password" name="password" value="admin123"><button class="btn">Login</button></form></body></html>
