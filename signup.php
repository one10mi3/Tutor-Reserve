<!-- html構成 -->
<?php
$title = 'tutor-reserve!|sign up';
$is_home =  false; //トップページの判定用の変数
// include 'inc/head.php'; // head.php の読み込み
include("inc/head.php");
 ?>

<body>

<div class="wrapper">
<?php include("inc/header.php"); ?>

<div class="container mt-5">

<h1>新規登録</h1>
<form class="row g-3" name="form1" action="signup_act.php" method="post" >
  <div class="col-md-6">
    <label for="inputLastName4" class="form-label">姓</label>
    <input type="text" name="last_name" class="form-control" id="inputLastName4" placeholder="山田" aria-label="Last name" required>
  </div>
  <div class="col-md-6">
    <label for="inputFirstName4" class="form-label">名</label>
    <input type="text" name="first_name" class="form-control" id="inputFirstName4" placeholder="太郎" aria-label="First name" required>
  </div>
  
  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">メールアドレス</label>
    <input type="email" name="email" class="form-control" id="inputEmail4" required>
    <span id="view" class="" style="color: red;">
      <?=$view?>
    </span>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">パスワード</label>
    <input type="password" name="password" class="form-control" id="inputPassword4" required>
  </div>
  
  <div class="col-12">
    <button type="submit" class="btn btn-primary">登録</button>
  </div>
</form>

<a href="login.php">ログイン</a>



</div>


</div>
<?php include("inc/footer.php"); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript">
  $submitchk = false;
//登録ボタンをクリック
$("#inputEmail4").on("keyup", function() {
    //axiosでAjax送信
    //Ajax（非同期通信）
    const params = new URLSearchParams();
    params.append('email', $("#inputEmail4").val());
    // debugger;
    //axiosでAjax送信
    axios.post('email_check.php', params).then(function (response) {
        // console.log(typeof response.data);//通信OK
        // console.log(response.data);
        if (response.data == "OK") {
          $submitchk = true;
        } else if (response.data) {
          $submitchk = false;
          // >>>>通信でデータを受信したら処理をする場所<<<<
          document.querySelector("#view").innerHTML=response.data;
        } 
    }).catch(function (error) {
        console.log(error);//通信Error
    }).then(function () {
        // console.log("Last");//通信OK/Error後に処理を必ずさせたい場合
    });
});
function submitchk() {
  // console.log($submitchk);
  return $submitchk;
  // if ($submitchk) {
  //   return true;
  // } else {
  //   return false;
  // }
}
</script>
</body>
</html>
