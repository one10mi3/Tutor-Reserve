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

// ユーザースキル情報取得（複数レコードの中から日付が最新のもの取得）
$sql = "SELECT * FROM tr_skills WHERE user_id = :user_id";
$sql = <<<EOF
SELECT `skill_id`, `skills`, `comment`, `user_id`, `update_time`
FROM `tr_skills` 
WHERE `update_time` = (
    SELECT MAX(update_time)
    FROM `tr_skills` AS ts
    WHERE `tr_skills`.user_id = ts.user_id
) AND `tr_skills`.user_id = ?;
EOF;
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1 , $val["user_id"], PDO::PARAM_INT);
$status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else{
  $user_skills = $stmt->fetch();
  $arr = explode(',', $user_skills["skills"]);
}

// スキルリストを取得
$sql = "SELECT * FROM tr_skill_lists";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

$skills="";
if($status==false) {
  sql_error($stmt);
}else{
  $skill_lists = $stmt->fetchAll();
  // echo count($skill_lists); // カウント 6
  foreach ($skill_lists as $index => $skill_list) {
    foreach ($arr as $a) {
      if ($skill_list[0] == $a){
        // echo $index;
        // echo "--------";
        // pre_print($skill_list[1]);
        $skills .= $skill_list[1];
        $skills .= "<br>";
      }
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
    <h1 class="fs-2">スキル情報</h1>
    <table class="table table-striped table-bordered">
      <tbody>
        <tr>
          <th scope="row">持っているスキル</th>
          <td><?PHP echo $skills ?></td>
        </tr>
        <tr>
          <th scope="row">コメント</th>
          <td><?php echo $user_skills["comment"]; ?></td>
        </tr>
      </tbody>
    </table>
    
    <a href="skill_insert.php" class="btn btn-secondary btn-lg" tabindex="-1" role="button" >変更</a>

  </div>
</div>

</div>
</div>
<?php include("inc/footer.php"); ?>



</body>
</html>
