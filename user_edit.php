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
$sql = "SELECT `period_id`, `name`, `delete_flg` 
FROM `tr_periods`
WHERE `delete_flg` = 1
ORDER BY `name` ASC";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

$inputview="";
if($status==false) {
    sql_error($stmt);
}else{
    $inputview .= '<select  name="period_id" required class="form-select" ';
    $inputview .= isset($val["period_id"]) ? 'disabled=disabled' : '';
    $inputview .= '>';
    while( $row = $stmt->fetch()){
      if($val["period_id"] == $row["period_id"]) {
        $inputview .= '<option value="'.$row["period_id"].'" selected >';
      } else {
        $inputview .= '<option value="'.$row["period_id"].'">';
      }
      $inputview .= $row["name"];
      $inputview .= '</option>';
    }
    $inputview .= '</select>';
    
}

?>


<!-- html構成 -->
<?php
$title = 'tutor-reserve!|user edit';
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
    <h1 class="fs-2">登録情報編集</h1>
<form class="row g-3" name="form1" action="user_update.php" method="post" >
  <div class="col-md-6">
    <label for="inputLastName4" class="form-label">姓</label>
    <input type="text" name="last_name" class="form-control" id="inputLastName4" placeholder="山田" aria-label="Last name" required value="<?php echo $val["last_name"]; ?>">
  </div>
  <div class="col-md-6">
    <label for="inputFirstName4" class="form-label">名</label>
    <input type="text" name="first_name" class="form-control" id="inputFirstName4" placeholder="太郎" aria-label="First name" required value="<?php echo $val["first_name"]; ?>">
  </div>

  <div class="col-6">
    <label for="inputnickname4" class="form-label">ニックネーム</label>
    <input type="text" name="nickname" class="form-control" id="inputnickname4" placeholder="はなちゃん" aria-label="nickname" required value="<?php echo $val["nickname"]; ?>">
  </div>
  <div class="col-6">
    <label for="inputnickname4" class="form-label">g's何期生<span style="color:red;"> ※1度登録したら変更できません</span></label>
    <?php echo $inputview; ?>
  </div>
  


  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Email</label>
    <input type="email" name="email" class="form-control" id="inputEmail4" required value="<?php echo $val["email"]; ?>">
    <span id="view" class="" style="color: red;">
      <?=$view?>
    </span>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Password</label>
    <input type="password" name="password" class="form-control" id="inputPassword4" placeholder="***********">
  </div>
  
  <input type="hidden" name="user_id" value="<?php echo $val["user_id"]; ?>">

  <div class="col-12">
    <button type="submit" class="btn btn-primary">変更</button>
  </div>
</form>
</div>

</div>
<?php include("inc/footer.php"); ?>


<!-- ajax -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript">
//登録ボタンをクリック
$("#inputEmail4").on("keyup", function() {
    //axiosでAjax送信
    //Ajax（非同期通信）
    const params = new URLSearchParams();
    params.append('email', $("#inputEmail4").val());
    
    //axiosでAjax送信
    axios.post('email_check.php', params).then(function (response) {
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
</script>


</body>
</html>
