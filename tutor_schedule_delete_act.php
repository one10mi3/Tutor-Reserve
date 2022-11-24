<?php
session_start();
include("function.php");
sschk();

$sid = $_SESSION["user_id"];
$val = user_check($sid);
if ( $val["kanri_flg"] == 0 ) {
  redirect("login.php");
}
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

// $day = $_POST["free_day"];
// $time = $_POST["free_time"]; 
$schedule_id = $_POST["schedule_id"]; 

// $objDateTime = new DateTime($day.$time);
// $objDateTime->format('Y-m-d H:i:s');
// $view .= $objDateTime;
// exit();

$alert="";
$pdo = db_conn();
// 空のスケジュールに新しく登録
$sql = "DELETE FROM  tr_tutor_schedules WHERE tutor_schedule_id=:schedule_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':schedule_id',$schedule_id, PDO::PARAM_INT);
$status = $stmt->execute();
if($status==false) {
  sql_error($stmt);
} else {
  $alert .= "空き削除しました";
}


echo $alert;
exit;
?>
