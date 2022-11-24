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
$sql = "SELECT * FROM `tr_users`";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

$users_list="";
if($status==false) {
  sql_error($stmt);
}else{
  $res = $stmt->fetchAll();
  // pre_print($users_list);
  if (!isset($res[0])) {
    $users_list .= '<p>登録がありません</p>';
  } else {
    foreach ($res as $index => $arr) {
      $users_list .= '<tr><th scope="row">'.$arr["user_id"].'</th>';
      $users_list .= '<td>';
      $users_list .= $arr["last_name"];
      $users_list .= '</td><td>';
      $users_list .= $arr["first_name"];
      $users_list .= '</td><td>';
      $users_list .= $arr["email"];
      $users_list .= '</td><td>';
      if ($arr["nickname"]) {
        $users_list .= $arr["nickname"];
      } else {
        $users_list .= '未登録';
      }
      $users_list .= '</td><td>';
      if ($arr["period_id"]) {
        $users_list .= $arr["period_id"];
      } else {
        $users_list .= '未登録';
      }
      $users_list .= '</td><td>';
      $users_list .= $arr["kanri_flg"];
      $users_list .= '</td><td>';
      $users_list .= $arr["delete_flg"];
      $users_list .= '</td><td>';
      $users_list .= $arr["update_time"];
      $users_list .= '</td><td>';
      $users_list .= '変更';
      $users_list .= '</td><td>';
      if ($arr["delete_flg"] == 1) { 
        $users_list .= '<a href="admin_user_delete.php?user_id='.$arr["user_id"].'">削除</a>';
      } else {
        $users_list .= '削除済';
      }

      $users_list .= '</td></tr>';
    }
  }
}

?>


<!-- html構成 -->
<?php
$title = 'tutor-reserve!|admin user list';
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
      <h5>■ユーザー一覧</h5>
      <p><a href="admin_user_insert.php">ユーザー新規登録</a></p>
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">姓</th>
            <th scope="col">名</th>
            <th scope="col">ニックネーム</th>
            <th scope="col">メールアドレス</th>
            <th scope="col">期生</th>
            <th scope="col">管理flg</th>
            <th scope="col">削除flg</th>
            <th scope="col">更新日</th>
            <th scope="col"></th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
            <?= $users_list; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

</div>
<?php include 'inc/footer.php'; ?>
</body>
</html>
