<?php
//SESSION開始
session_start();

$email = $_POST["email"];

include("function.php");
$pdo = db_conn();

// emailアドレスの重複チェック
$sql="SELECT * FROM tr_users WHERE email=:email";
$stmt = $pdo->prepare($sql); 
$stmt->bindValue(":email", $email, PDO::PARAM_STR);
$status = $stmt->execute(); 

$view="";
if($status==false){
  sql_error($stmt);
} else {
  $r = $stmt->fetch();
  if ($r[0]) {
    // $view .= "該当ID".$r[0];
    $view .= "すでに登録されているメールアドレスです";
  } else {
    $view = "OK";
    // $view = "false";
  }
}

echo $view;
exit;
?>
