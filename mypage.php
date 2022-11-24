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

$pdo = db_conn();
//過去の質問内容情報を取得(最新順 5件)
$sql = <<<EOF
SELECT `tr_user_schedules`.`content`, `tr_user_schedules`.`skill_list_id`, `tr_user_schedules`.`update_time`, `tr_skill_lists`.`name`
FROM `tr_user_schedules` 
INNER JOIN `tr_skill_lists` ON `tr_skill_lists`.`skill_list_id` = `tr_user_schedules`.`skill_list_id`
WHERE `tr_user_schedules`.`delete_flg` = 1 ORDER BY `update_time` DESC LIMIT 5
EOF;
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else{
  $qa_historys = $stmt->fetchAll();
  // pre_print($qa_historys);
  if (!isset($qa_historys[0])) {
    // echo "入ってない";
    $in_schedule = '<p>予約中のスケジュールはありません</p><p>予約したい場合は<a href="schedule.php">こちら</a></p>';
  } else {
    foreach ($qa_historys as $index => $arr) {
      $qa_history_list .= '<li class="list-group-item list-group-item-secondary">';
      $qa_history_list .= '質問内容：';
      $qa_history_list .= '【'.$arr["name"].'について】';
      $qa_history_list .= $arr["content"];
      $qa_history_list .= '<br>日付：';
      $qa_history_list .= $arr["update_time"];
      $qa_history_list .= '</li>';
    }
  }
}

//ログイン者が予約している情報を取得
$sql = <<<EOF
SELECT `tr_tutor_schedules`.`tutor_schedule_id`, `tr_tutor_schedules`.`free`, `tr_tutor_schedules`.`tutor_user_id`, `tr_user_schedules`.`content`, `tr_user_schedules`.`delete_flg`,`tr_users`.`last_name`,`tr_users`.`first_name`,`tr_users`.`nickname`,`tr_users`.`kanri_flg`
FROM `tr_tutor_schedules`
INNER JOIN `tr_user_schedules` ON `tr_tutor_schedules`.`tutor_schedule_id` = `tr_user_schedules`.`tutor_schedule_id`
INNER JOIN `tr_users` ON `tr_users`.`user_id` = `tr_tutor_schedules`.`tutor_user_id`
WHERE `tr_tutor_schedules`.`user_id` = ?
ORDER BY `tr_tutor_schedules`.`free` ASC
EOF;
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $val["user_id"], PDO::PARAM_INT);
$status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else{
  $my_schedules = $stmt->fetchAll();
  // pre_print($my_schedules);
  if (!isset($my_schedules[0])) {
    // echo "入ってない";
    $in_schedule = '<p>予約中のスケジュールはありません</p><p>予約したい場合は<a href="schedule.php">こちら</a></p>';
  } else {
    foreach ($my_schedules as $index => $arr) {
      $my_schedule_list .= '<tr><th scope="row">予約日</th><td>';
      $my_schedule_list .= $arr["free"];
      $my_schedule_list .= '</td></tr><tr><th scope="row">チューター名</th><td>';
      $my_schedule_list .= $arr["last_name"].$arr["first_name"].'(';
      if ($arr["nickname"]) {
        $my_schedule_list .= $arr["nickname"];
      } else {
        $my_schedule_list .= '未登録';
      }
      $my_schedule_list .= ')';
      $my_schedule_list .= '</td></tr>';
    }
  }
}


?>


<!-- html構成 -->
<?php
$title = 'tutor-reserve!|myapge';
$is_home =  false; //トップページの判定用の変数
include 'inc/head.php'; // head.php の読み込み
?>

<body>

<div class="wrapper">
<?php include("inc/header.php"); ?>

<div class="container mt-5">
  
  <?php include("inc/hello.php"); ?>

  <div class="row">
    <div class="col-md-3">
      <?php include("inc/side_menu.php"); ?>
    </div>
    <div class="col-md-9">
      <section>
        <h5>■過去の質問履歴<span class="fs-6">(新着順)</span></h5>
        <ul class="list-group">
          <?= $qa_history_list; ?>
        </ul>
      </section>

      <section>
        <h5 class="mt-5">■予約中のスケジュール</h5>
        <?= $in_schedule; ?>
        <table class="table table-striped">
          <tbody>
            <?= $my_schedule_list; ?>
          </tbody>
        </table>
      </section>

      <section>
        <h5  class="mt-5">■チューターからのメッセージ</h5>
        <p>チューターからのメッセージはありません</p>
      </section>

      <section>
        <h5 class="mt-5">■チューターの空き時間情報がない時は..</h5>
        <p>Helpアラートを<a href="help_form.php">送信</a>する（slack通知）</p>
      </section>
   
    </div>
  </div>
</div>

</div>
<?php include 'inc/footer.php'; ?>
</body>
</html>
