<!-- html構成 -->
<?php
$title = 'tutor-reserve!|login';
$is_home =  false; //トップページの判定用の変数
include 'inc/head.php'; // head.php の読み込み
?>

<body>

<div class="wrapper">
<?php include 'inc/header.php'; ?>

<div class="container mt-5">

<h1>ログイン</h1>
<form class="row g-3" name="form1" action="login_act.php" method="post">
  
  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">メールアドレス</label>
    <input type="email" name="email" class="form-control" id="inputEmail4" required>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">パスワード</label>
    <input type="password" name="password" class="form-control" id="inputPassword4" required>
  </div>
  
  <div class="col-12">
    <button type="submit" class="btn btn-primary">送信</button>
  </div>
</form>

<a class="nav-link" href="signup.php">新規登録</a>

</div>


</div>
<?php include 'inc/footer.php'; ?>
</body>
</html>
