<?php
session_start();
include("function.php");
sschk();

$sid = $_SESSION["user_id"];
$val = user_check($sid);
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

//1. POSTデータ取得
$user_id     = $_POST["user_id"];
$last_name   = $_POST["last_name"];
$first_name   = $_POST["first_name"];
$nickname  = $_POST["nickname"];
$email  = $val["email"];
$password = $val["password"];
if ($_POST["password"]) {
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT); //暗号化
  // echo "あるよ";
} else {
  $password = $val["password"];
}
$period_id  = $_POST["period_id"];

$pdo = db_conn();
$sql = "UPDATE tr_users SET last_name=:last_name, first_name=:first_name, nickname=:nickname, email=:email, password=:password, period_id=:period_id WHERE user_id=:user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
$stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
$stmt->bindValue(':nickname', $nickname, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$stmt->bindValue(':period_id', $period_id, PDO::PARAM_INT);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$status = $stmt->execute(); //実行

if($status==false){
  sql_error($stmt);
}else{
  redirect("user_show.php");
}
?>
