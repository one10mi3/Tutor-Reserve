<?php
//SESSION開始
session_start();
include("function.php");

//LOGINチェック
sschk();

$sid = $_SESSION["user_id"];

$val = user_check($sid);
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

echo "未実装です";
// echo $sid;
// echo $val["last_name"];

?>

