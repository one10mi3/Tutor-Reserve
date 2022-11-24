<?php
session_start();
include("function.php");
sschk();

$sid = $_SESSION["user_id"];
$val = user_check($sid);
if (!$val["kanri_flg"] == 3) {
  redirect("login.php");
}
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

$user_id = $_GET["user_id"];

$alert="";
$pdo = db_conn();
$sql = "UPDATE tr_users SET delete_flg=:delete_flg WHERE user_id=:user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':delete_flg', 0, PDO::PARAM_INT);
$status = $stmt->execute();
if($status==false) {
  sql_error($stmt);
} else {
  redirect("admin_user_list.php");
}

exit;
?>
