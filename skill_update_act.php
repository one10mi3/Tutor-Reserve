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
$comment = $_POST["comment"];
// echo $comment;
$user_id = $val["user_id"];

// exit();

$pdo = db_conn();
$sql = "UPDATE tr_skills SET skills=:skills, comment=:comment, user_id=:user_id WHERE user_id=:user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':skills', $skills, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$status = $stmt->execute();

if ($status == false) {
  sql_error($stmt);
} else {
  redirect("user_show.php");
}
// exit();

?>



