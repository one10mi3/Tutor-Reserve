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
$sql = "SELECT * FROM `tr_periods`";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

$periods_list="";
if($status==false) {
  sql_error($stmt);
}else{
  $res = $stmt->fetchAll();
  // pre_print($periods_list);
  if (!isset($res[0])) {
    $periods_list .= '<p>登録がありません</p>';
  } else {
    foreach ($res as $index => $arr) {
      $periods_list .= '<tr><th scope="row">'.$arr["period_id"].'</th>';
      $periods_list .= '<td>';
      $periods_list .= $arr["name"];
      $periods_list .= '</td><td>';
      $periods_list .= $arr["delete_flg"];
      $periods_list .= '</td><td>';
      $periods_list .= $arr["update_time"];
      $periods_list .= '</td><td>';
      $periods_list .= '変更';
      $periods_list .= '</td><td>';
      if ($arr["delete_flg"] == 1) { 
        $periods_list .= '<a href="admin_period_delete.php?period_id='.$arr["period_id"].'">削除</a>';
      } else {
        $periods_list .= '削除済';
      }

      $periods_list .= '</td></tr>';
    }
  }
}

?>


<!-- html構成 -->
<?php
$title = 'tutor-reserve!|admin period list';
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
      <h5>■期生一覧</h5>
      <p><a href="admin_period_insert.php">期生新規登録</a></p>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">期生名</th>
            <th scope="col">削除flg</th>
            <th scope="col">更新日</th>
            <th scope="col"></th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
            <?= $periods_list; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

</div>
<?php include 'inc/footer.php'; ?>
</body>
</html>
