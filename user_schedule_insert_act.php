<?php
session_start();
include("function.php");
sschk();

$sid = $_SESSION["user_id"];
$val = user_check($sid);
// echo "わたしの".$val["user_id"];
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

// $tutor_schedule_id = $_POST["tutor_schedule_id"];
$skill_list_id = $_POST["skill_list_id"];
$tutor_schedule_id = $_POST["flexRadioDefault"];
$content = $_POST["content"];
// echo $tutor_schedule_id;
// echo  $content;

$pdo = db_conn();
$sql = "UPDATE tr_tutor_schedules SET user_id=:user_id WHERE tutor_schedule_id=:tutor_schedule_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $val["user_id"], PDO::PARAM_INT);
$stmt->bindValue(':tutor_schedule_id', $tutor_schedule_id, PDO::PARAM_INT);
$status = $stmt->execute(); //実行

$insert_flg = true;
if($status==false){
  sql_error($stmt);
  $insert_flg = false;
}

if ($insert_flg){
  $sql = "INSERT INTO tr_user_schedules(tutor_schedule_id, content, skill_list_id)VALUES(:tutor_schedule_id, :content, :skill_list_id);";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':tutor_schedule_id', $tutor_schedule_id, PDO::PARAM_INT);
  $stmt->bindValue(':content', $content, PDO::PARAM_STR);
  $stmt->bindValue(':skill_list_id', $skill_list_id, PDO::PARAM_INT);
  $status = $stmt->execute(); //実行

  if($status==false){
    sql_error($stmt);
  } else {
    redirect("mypage.php");
  }

}


?>
