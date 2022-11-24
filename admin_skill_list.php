<?php
session_start();
include("function.php");
sschk();

$sid = $_SESSION["user_id"];
$val = user_check($sid);
if (!$val["kanri_flg"] == 3) {
  redirect("login.php");
}
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

$pdo = db_conn();
$sql = "SELECT * FROM `tr_skill_lists`";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

$skill_lists_list="";
if($status==false) {
  sql_error($stmt);
}else{
  $res = $stmt->fetchAll();
  // pre_print($skill_lists_list);
  if (!isset($res[0])) {
    $skill_lists_list .= '<p>登録がありません</p>';
  } else {
    foreach ($res as $index => $arr) {
      $skill_lists_list .= '<tr><th scope="row">'.$arr["skill_list_id"].'</th>';
      $skill_lists_list .= '<td>';
      $skill_lists_list .= $arr["name"];
      $skill_lists_list .= '</td><td>';
      $skill_lists_list .= $arr["delete_flg"];
      $skill_lists_list .= '</td><td>';
      $skill_lists_list .= $arr["update_time"];
      $skill_lists_list .= '</td><td>';
      $skill_lists_list .= '変更';
      $skill_lists_list .= '</td><td>';
      if ($arr["delete_flg"] == 1) { 
        $skill_lists_list .= '<a href="admin_skill_list_delete.php?skill_list_id='.$arr["skill_list_id"].'">削除</a>';
      } else {
        $skill_lists_list .= '削除済';
      }

      $skill_lists_list .= '</td></tr>';
    }
  }
}

?>


<!-- html構成 -->
<?php
$title = 'tutor-reserve!|admin skill list';
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
    <!-- 中身 -->
      <h5>■スキル一覧</h5>
      <p><a href="admin_skill_list_insert.php">スキル新規登録</a></p>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">スキル名</th>
            <th scope="col">削除flg</th>
            <th scope="col">更新日</th>
            <th scope="col"></th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
            <?= $skill_lists_list; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

</div>
<?php include 'inc/footer.php'; ?>
</body>
</html>
