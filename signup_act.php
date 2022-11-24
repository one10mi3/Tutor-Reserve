<?php
//SESSION開始
session_start();

//POST値
$last_name = $_POST["last_name"];
$first_name = $_POST["first_name"];
$email = $_POST["email"];
// echo $last_name;
// echo $first_name;
// echo $email;
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
// echo $password;


include("function.php");
$pdo = db_conn();

// emailアドレスの重複チェック
// $sql="SELECT * FROM tr_users WHERE  email=:email";
// $stmt = $pdo->prepare($sql); 
// $stmt->bindValue(':email', $email, PDO::PARAM_STR);
// if($status==false){
//   sql_error($stmt);
//   echo "重複なし";
// } else {
//   echo "重複あり";
// }

// ユーザー登録実行
$sql = "INSERT INTO tr_users(last_name, first_name, email, password)VALUES(:last_name, :first_name, :email, :password);";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
$stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);
$status = $stmt->execute(); //実行

if($status==false){
  sql_error($stmt);
}

// ユーザー登録したID取得
$sql = "SELECT LAST_INSERT_ID()";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if($status==false){
  sql_error($stmt);
} else {
  $insert_id = $stmt->fetch();
  $val = user_check($insert_id[0]);
  $_SESSION["chk_ssid"]  = session_id();
  $_SESSION["user_id"]   = $val["user_id"];
  redirect("mypage.php");
}

?>
