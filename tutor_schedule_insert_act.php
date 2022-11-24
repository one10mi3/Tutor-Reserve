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

$day = $_POST["free_day"];
$time = $_POST["free_time"]; 

$objDateTime = new DateTime($day.$time);
// $objDateTime->format('Y-m-d H:i:s');
// $view .= $objDateTime;
// exit();

$view="";
// $view=array();
$pdo = db_conn();
// 空のスケジュールに新しく登録
$sql= "INSERT INTO tr_tutor_schedules(tutor_user_id,  free, free_day, free_time)VALUES(:tutor_user_id, :free, :free_day, :free_time);";
$stmt = $pdo->prepare($sql); 
$stmt->bindValue(":tutor_user_id", $val["user_id"], PDO::PARAM_INT);
$stmt->bindValue(":free", $objDateTime->format('Y-m-d H:i:s'), PDO::PARAM_STR);
$stmt->bindValue(":free_day", $objDateTime->format('Y-m-d'), PDO::PARAM_STR);
$stmt->bindValue(":free_time", $objDateTime->format('H:i:s'), PDO::PARAM_STR);
$status = $stmt->execute(); 
// $view .= $day;
// $view .= $objDateTime->format('Y-m-d H:i:s');
$slack_notice = false;
if($status==false) {
  sql_error($stmt);
} else {
  $slack_notice = true;
}
// 空き情報の登録がされたことを
// slackへ通知
if ($slack_notice == true) {
  $content  = $val["last_name"].$val["first_name"];
  $content .= "さんから";
  $content .= "\n";
  $content .= $objDateTime->format('Y-m-d H:i');
  $content .= "の";
  $content .= "\n";
  $content .= "空き情報が登録されました";

  $url = "https://hooks.slack.com/services/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";
  $message = [
    "channel" => "#tutor_reserve",
    "text" => $content
  ];
  $ch = curl_init();
  $options = [
    CURLOPT_URL => $url,
    // 返り値を文字列で返す
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    // POST
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
      'payload' => json_encode($message)
    ])
  ];
  curl_setopt_array($ch, $options);
  curl_exec($ch);
  curl_close($ch);
}





// 空き情報登録したIDだけを取得
$sql = "SELECT LAST_INSERT_ID()";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
if($status==false){
  sql_error($stmt);
} else {
  $insert_id = $stmt->fetch();
  $view .= "空き登録しました";
  $view .= "&".$insert_id[0];
}


echo $view;
exit;
?>
