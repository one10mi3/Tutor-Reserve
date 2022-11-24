<?php
//SESSION開始
session_start();
include("function.php");

//LOGINチェック
sschk();

$sid = $_SESSION["user_id"];
$val = user_check($sid);
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

$view = "未実装です";

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
    <!-- 左 -->
    <div class="col-md-3">
      <?php include("inc/side_menu.php"); ?>
    </div>
    <!-- 右 -->
    <div class="col-md-9">
      <!-- 中身 -->
      <?= $view; ?>
    </div>
  </div>
</div>

</div>
<?php include 'inc/footer.php'; ?>
</body>
</html>
