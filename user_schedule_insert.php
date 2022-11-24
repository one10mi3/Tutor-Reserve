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

$day = $_GET["day"];
$time = $_GET["time"];
$day_set = $day." ". $time;

$pdo = db_conn();
// 該当日スケジュールのチューター空き情報取得（自分がチューターで登録した情報も含む、予約されているものは含まない）
$sql = <<<EOF
SELECT `tr_tutor_schedules`.`tutor_schedule_id`, `tr_tutor_schedules`.`free`, `tr_tutor_schedules`.`user_id`, `tr_users`.`first_name`, `tr_users`.`last_name`, `tr_users`.`nickname`, `tr_users`.`kanri_flg`
FROM `tr_tutor_schedules`
INNER JOIN `tr_users` ON `tr_tutor_schedules`.`tutor_user_id` = `tr_users`.`user_id`
WHERE `free` = ? AND `tr_tutor_schedules`. `user_id` IS NULL
EOF;
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $day_set, PDO::PARAM_STR);
$status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else{
  $tutor_schedules = $stmt->fetchAll();
  // pre_print($tutor_schedules);
  
}

$count = count($tutor_schedules);

//ログイン者が予約している情報を取得
$sql = <<<EOF
SELECT `tr_tutor_schedules`.`tutor_schedule_id`, `tr_tutor_schedules`.`free`, `tr_tutor_schedules`.`user_id`, `tr_users`.`first_name`, `tr_users`.`last_name`, `tr_users`.`nickname`, `tr_users`.`kanri_flg`
FROM `tr_tutor_schedules`
INNER JOIN `tr_users` ON `tr_tutor_schedules`.`tutor_user_id` = `tr_users`.`user_id`
WHERE `free` = ? AND `tr_tutor_schedules`.`user_id` = ?
EOF;
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $day_set, PDO::PARAM_STR);
$stmt->bindValue(2, $val["user_id"], PDO::PARAM_INT);
$status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else{
  $my_schedules = $stmt->fetch();
  // pre_print($my_schedules);
}
// 予約している場合
if ($my_schedules) {
  $count = $count - 1;
}

//空き情報のみを取得（自分が登録した空き情報は含まない）
$sql = <<<EOF
SELECT `tr_tutor_schedules`.`tutor_schedule_id`, `tr_tutor_schedules`.`free`, 
`tr_tutor_schedules`.`user_id` as `help_user_id`, 
`tr_users`.`first_name`, `tr_users`.`last_name`, `tr_users`.`nickname`, `tr_users`.`kanri_flg`,`tr_users`.`user_id`
FROM `tr_tutor_schedules`
INNER JOIN `tr_users` ON `tr_tutor_schedules`.`tutor_user_id` = `tr_users`.`user_id`
WHERE `free` = ? AND `tr_tutor_schedules`.`user_id` IS NULL
EOF;
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $day_set, PDO::PARAM_STR);
$status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else{
  $free_schedules = $stmt->fetchAll();
  // pre_print($free_schedules);
  if (!isset($free_schedules[0])) {
    $tutors .= '<p>いません</p>';
  } else {
    foreach ($free_schedules as $index => $arr) {

      if ($arr["user_id"] == $val["user_id"]){
        $count = $count - 1;
      } else {
        $tutors .= '<div class="form-check">';
        $tutors .= '<input class="form-check-input" type="radio" name="flexRadioDefault"  value="'.$arr["tutor_schedule_id"].'" id="flexRadioDefault'.$index.'" checked>';
        $tutors .= '<label class="form-check-label" for="flexRadioDefault'.$index.'">';
        $tutors .= $arr["last_name"].$arr["first_name"];
        if ($arr["kanri_flg"] == 1) {
          $tutors .= '(チューター)';
        } elseif($arr["kanri_flg"] == 2) {
          $tutors .= '(サブチューター)';
        } elseif($arr["kanri_flg"] == 3) {
          $tutors .= '(管理者)';
        }
        $tutors .= '&nbsp;&nbsp;<a href="user_profile.php?user_id='.$arr["user_id"].'">プロフィール確認</a>';
        $tutors .= '</label>';
        $tutors .= '</div>';
      }
      
    }
  }
}


// スキル全リストを取得
$stmt = $pdo->prepare("SELECT skill_list_id, name FROM tr_skill_lists WHERE delete_flg=1");
$status = $stmt->execute();

$view = "";
if($status==false) {
  sql_error($stmt);
}else{
  $skill_lists = $stmt->fetchAll();
  // pre_print($skill_lists);
  foreach ($skill_lists as $v) {
    // echo $v["skill_list_id"];
    // echo $v["name"];
    $view .= '<option value="'.$v["skill_list_id"].'">'.$v["name"].'について</option>';
  }
}

?>


<!-- html構成 -->
<?php
$title = 'tutor-reserve!|user_schedule_insert';
$is_home =  false; //トップページの判定用の変数
include 'inc/head.php'; // head.php の読み込み
?>

<body>

<div class="wrapper">
<?php include("inc/header.php"); ?>

<div class="container mt-5">
  
<?php include("inc/hello.php"); ?>

  <div class="row">
    <!-- 左カラム -->
    <div class="col-md-3">
      <?php include("inc/side_menu.php"); ?>
    </div>

    <!-- 右カラム -->
    <div class="col-md-9">
      <h5>■予約フォーム</h5>

      
      <?php if ($count == 0) :?>
        <p>空きのあるチューターがすべて予約されています</p>
      <?php else :?>
        <p><?= $count ?>人、空いてるチューターがいます！</p>
      <?php endif; ?>

      <?php if ($count > 0): ?>
      <p class="mt-5">【予約日】<?= $tutor_schedules[0]["free"]; ?></p>
      <form action="user_schedule_insert_act.php" method="post">

        <!-- チューター選択 -->
        <div class="col-12 mt-5">
          <p>チューターを選択してください</p>
          <?= $tutors; ?>
        </div>

        <div class="col-12 mt-5">
          <p style="color:red;">※質問内容は全体へ公開されます</p>

          <select class="form-select" name="skill_list_id" aria-label="Default select example" required>
            <?= $view; ?>
          </select>

          <textarea class="form-control mt-3" name="content" placeholder="質問内容を入力" id="floatingTextarea2" style="height: 100px" required></textarea>
        </div>

        <?php if (count($tutor_schedules) > 0): ?>
          <?php if ($my_schedules): ?>
            <div class="col-12 mt-2">
              <button class="btn btn-primary" disabled>予約済</button>
            </div>
            <?php else: ?>
            <div class="col-12 mt-2">
              <button type="submit" class="btn btn-primary">予約</button>
            </div>
          <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>

</div>
<?php include 'inc/footer.php'; ?>


</body>
</html>
