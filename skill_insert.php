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

// スキルリスト取得
$stmt = $pdo->prepare("SELECT * FROM tr_skill_lists");
$status = $stmt->execute();

$view="";
if($status==false) {
    sql_error($stmt);
}else{
    while( $row = $stmt->fetch() ) {
        $view .= '<div class="form-check">';
        $view .= '<label class="form-check-label" for="flexCheckChecked'.$row["skill_list_id"].'">';
        $view .= '<input class="form-check-input" type="checkbox" name="skills[]" value="'.$row["skill_list_id"].'" id="flexCheckChecked'.$row["skill_list_id"].'" ';
        foreach ($arr as $a) {
          if ($row["skill_list_id"] == $a){
            $view .= "checked";
          }
        }
        $view .= '>';
        $view .= $row["name"];
        $view .= '</label></div>';
    }
}


// スキルリスト取得2
$stmt = $pdo->prepare("SELECT * FROM tr_skill_lists");
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
$title = 'tutor-reserve!|skill insert';
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
    <h5>■スキル情報</h5>

    <?php if ($skills==null): ?>
      <!-- 未登録 -->
    
      <form class="row g-3" name="form1" action="skill_insert_act.php" method="post" >

        <div class="col-12">
          <?php echo $view; ?>
        </div>

        <div class="col-12">
          <div class="form-floating">
            <textarea class="form-control" name="comment" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"><?php echo $user_skills["comment"]; ?></textarea>
            <label for="floatingTextarea2"></label>
          </div>
        </div>
        
        <input type="hidden" name="user_id" value="<?php echo $val["user_id"]; ?>">
        <input type="hidden" name="kanri_flg" value="<?php echo $val["kanri_id"]; ?>">

        <div class="col-12">
          <button type="submit" class="btn btn-primary">登録</button>
        </div>
      </form>

    <?php else: ?>
      <!-- 登録済 -->

      <form class="row g-3" name="form1" action="skill_update_act.php" method="post" >

        <div class="col-12">
          <?php echo $view; ?>
        </div>

        <div class="col-12">
            <textarea class="form-control" name="comment" placeholder="コメント" id="floatingTextarea2" style="height: 100px"><?php echo $user_skills["comment"]; ?></textarea>
        </div>
        
        <input type="hidden" name="user_id" value="<?php echo $val["user_id"]; ?>">

        <div class="col-12">
          <button type="submit" class="btn btn-primary">変更</button>
        </div>
      </form>
    <?php endif; ?>





  </div>
</div>

</div>
</div>
<?php include("inc/footer.php"); ?>



</body>
</html>
