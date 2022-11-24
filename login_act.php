<?php
session_start();

$email = $_POST["email"];
$password = $_POST["password"];

include("function.php");
$pdo = db_conn();

$sql="SELECT * FROM tr_users WHERE email=:email AND delete_flg=1";
$stmt = $pdo->prepare($sql); 
$stmt->bindValue(':email', $email,PDO::PARAM_STR);
$status = $stmt->execute();

if($status==false){
    sql_error($stmt);
}

$val = $stmt->fetch();

$pw = password_verify($password, $val["password"]); //pass一致確認 true or false

if( $pw ){ 
  //Login成功時
  $_SESSION["chk_ssid"]  = session_id();
  $_SESSION["user_id"]   = $val["user_id"];
  redirect("mypage.php");
}else{
  redirect("login.php");
}

?>
