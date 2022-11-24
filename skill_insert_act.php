<?php
session_start();
include("function.php");
sschk();

$sid = $_SESSION["user_id"];
$val = user_check($sid);
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

$arr = $_POST["skills"];
$skills = implode(',', $arr);
// print($skills);
// exit();
$comment = $_POST["comment"];
// echo $comment;
$user_id = $val["user_id"];
// echo $user_id;
$kanri_flg = $val["kanri_flg"];
if ($val["kanri_flg"] == 0){
  $kanri_flg = 2;
}
// echo $kanri_flg;

// exit();

$pdo = db_conn();
$sql = "INSERT INTO tr_skills(skills,comment,user_id)VALUES(:skills,:comment,:user_id)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':skills', $skills, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$status = $stmt->execute();

$insert_flg = false;
if ($status == false) {
  sql_error($stmt);
} else {
  $insert_flg = true;
  // echo "OK";
  // exit();
}

// スキルが登録できたらユーザー情報kanri_flgを変更
if ($insert_flg) {
  // echo "OK";
  // exit();
  $sql = "UPDATE tr_users SET kanri_flg=:kanri_flg WHERE user_id=:user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':kanri_flg', $kanri_flg, PDO::PARAM_INT);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
  $status = $stmt->execute();
}
if ($status == false) {
  sql_error($stmt);
} else {
  redirect("user_show.php");
}

?>



