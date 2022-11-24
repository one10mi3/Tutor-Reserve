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

$user_id = $_GET["user_id"];

$pdo = db_conn();

// ユーザー情報取得
$sql = <<<EOF
SELECT `tr_users`.`user_id`, 
`tr_users` .`last_name`, 
`tr_users` .`first_name`, 
`tr_users` .`nickname`, 
`tr_users` .`period_id`, 
`tr_users` .`kanri_flg`, 
`tr_users` .`delete_flg`,
`tr_skills`.`skill_id`,
`tr_skills`.`skills`,
`tr_skills`.`comment`,
`tr_skills`.`user_id`
FROM `tr_users` 
LEFT JOIN `tr_skills` ON `tr_skills`.`user_id` = `tr_users`.`user_id`
WHERE `tr_users`.`user_id` = ? 
EOF;
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $user_id, PDO::PARAM_INT);
$status = $stmt->execute();


if ($status == false) {
  sql_error($stmt);
} else {
  $user = $stmt->fetch();
  // pre_print($user);
  // exit();
  $user_skill = explode(',', $user["skills"]);
  // pre_print($skills);
  // exit();
}

// 期生のリストを取得
$stmt = $pdo->prepare("SELECT * FROM tr_periods WHERE period_id=:period_id");
$stmt->bindValue(":period_id", $user["period_id"], PDO::PARAM_INT);
$status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else{
  $row = $stmt->fetch();
  // pre_print($row);
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
    if (in_array($v["skill_list_id"], $user_skill)){
      // echo $v["name"];
      $view .= $v["name"].'&nbsp;';
    }

  }
}


?>


<!-- html構成 -->
<?php
$title = 'tutor-reserve!|user_show';
$is_home =  false; //トップページの判定用の変数
include("inc/head.php");
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
    <h5>■プロフィール</h5>

    <table class="table table-striped table-bordered">
      <tbody>
        <tr>
          <th scope="row"  class="col-4">スキル</th>
          <td>
            <?= $view; ?>
          </td>
        </tr>

        <tr>
          <th scope="row"  class="col-4">コメント</th>
          <td>
            <?php echo isset($user["comment"]) ? $user["comment"]: '未登録'; ?>
          </td>
        </tr>
      </tbody>
    </table>


    <table class="table table-striped table-bordered">
      <tbody>
        <tr>
          <th scope="row" class="col-4">名前</th>
          <td><?php echo $user["last_name"]; ?><?php echo $user["first_name"]; ?></td>
        </tr>
        <tr>
          <th scope="row">ニックネーム</th>
          <td><?php echo isset($user["nickname"]) ? $user["nickname"] : '未登録'; ?></td>
        </tr>
        <tr>
          <th scope="row">何期生</th>
          <td><?php echo isset($user["period_id"]) ? $row["name"] : '未登録'; ?></td>
        </tr>
      </tbody>
    </table>


    <table class="table table-striped table-bordered mt-5">
      <tbody>
        <tr>
          <th scope="row" class="col-4">プロフィール画像</th>
          <td>
            <?php echo isset($user["image_id"]) ? '登録済': '未登録'; ?>
          </td>
        </tr>
      </tbody>
    </table>




  </div>
</div>

</div>
</div>
<?php include("inc/footer.php"); ?>



</body>
</html>
