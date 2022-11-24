<?php
//SESSION開始
session_start();
include("function.php");
sschk();

$sid = $_SESSION["user_id"];
$val = user_check($sid);
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

// slackへ通知
$content  = $val["last_name"].$val["first_name"];
$content .= "さんから";
$content .= "\n";
$content .= "誰か助けて〜";
$content .= "の";
$content .= "\n";
$content .= "送信がされました";

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


redirect("mypage.php");
