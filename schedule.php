<?php
ini_set('display_errors',1);
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

// チューターが登録している空き情報をとってくる(日付が過ぎているものは除外、予約されているものも除外）
$sql = <<<EOF
SELECT `tutor_schedule_id`, `tutor_user_id`, `user_id`, `free`, `free_day`, `free_time`, `update_time`  
FROM `tr_tutor_schedules`
WHERE `free` >= CURRENT_TIMESTAMP AND `tr_tutor_schedules`. `user_id` IS NULL
EOF;
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
if($status==false) {
  sql_error($stmt);
}else{
  $tutor_schedules = $stmt->fetchAll();
  // pre_print($tutor_schedules);
}

// 登録済みのスケジュール情報取得
// $sql = "SELECT * FROM tr_tutor_schedules WHERE user_id=:user_id";
// $stmt = $pdo->prepare($sql);
// $stmt->bindValue(":user_id", $val["user_id"], PDO::PARAM_INT);
// $status = $stmt->execute();

// if($status==false) {
//   sql_error($stmt);
// }else{
//   $schedules = $stmt->fetchAll();
//   // pre_print($schedules);
//   // echo  $schedules[0]["free_time"];
// }

// 1.週の構成
// echo date('Y年m月d日 H時i分s秒'); // 現在の時間
$days = array();
$weeks = array();
$day = date('w');
$date = new DateTime();
$weeks[0]["normal"] = $date->format('j');
$weeks[0]["plus"] = $date->format('j').'('.week_day_custom($day).')';
$days[] = $date->format('Y-m-d');

$date->add(new DateInterval('P1D'));
$day = $day + 1;
$day == 7 ? $day = 0 : "";
$weeks[1]["normal"] = $date->format('j');
$weeks[1]["plus"] = $date->format('j').'('.week_day_custom($day).')';
$days[] = $date->format('Y-m-d');
$date->add(new DateInterval('P1D'));
$day = $day + 1;
$day == 7 ? $day = 0 : "";
$weeks[2]["normal"] = $date->format('j');
$weeks[2]["plus"] = $date->format('j').'('.week_day_custom($day).')';
$days[] = $date->format('Y-m-d');
$date->add(new DateInterval('P1D'));
$day = $day + 1;
$day == 7 ? $day = 0 : "";
$weeks[3]["normal"] = $date->format('j');
$weeks[3]["plus"] = $date->format('j').'('.week_day_custom($day).')';
$days[] = $date->format('Y-m-d');
$date->add(new DateInterval('P1D'));
$day = $day + 1;
$day == 7 ? $day = 0 : "";
$weeks[4]["normal"] = $date->format('j');
$weeks[4]["plus"] = $date->format('j').'('.week_day_custom($day).')';
$days[] = $date->format('Y-m-d');
$date->add(new DateInterval('P1D'));
$day = $day + 1;
$day == 7 ? $day = 0 : "";
$weeks[5]["normal"] = $date->format('j');
$weeks[5]["plus"] = $date->format('j').'('.week_day_custom($day).')';
$days[] = $date->format('Y-m-d');
$date->add(new DateInterval('P1D'));
$day = $day + 1;
$day == 7 ? $day = 0 : "";
$weeks[6]["normal"] = $date->format('j');
$weeks[6]["plus"] = $date->format('j').'('.week_day_custom($day).')';
$days[] = $date->format('Y-m-d');

$view = "";
$view .= '<th scope="col" data-value="'.$weeks[0]["normal"].'">'.$weeks[0]["plus"].'</th>';
$view .= '<th scope="col" data-value="'.$weeks[1]["normal"].'">'.$weeks[1]["plus"].'</th>';
$view .= '<th scope="col" data-value="'.$weeks[2]["normal"].'">'.$weeks[2]["plus"].'</th>';
$view .= '<th scope="col" data-value="'.$weeks[3]["normal"].'">'.$weeks[3]["plus"].'</th>';
$view .= '<th scope="col" data-value="'.$weeks[4]["normal"].'">'.$weeks[4]["plus"].'</th>';
$view .= '<th scope="col" data-value="'.$weeks[5]["normal"].'">'.$weeks[5]["plus"].'</th>';
$view .= '<th scope="col" data-value="'.$weeks[6]["normal"].'">'.$weeks[6]["plus"].'</th>';


// 2. 時間の構成
$time_lists = array();

// 表の初期の時間設定
$dateTime1 = '00:00';
// 1つ目いれる
$time_lists[] = $dateTime1;
// 2つ目以降
$str="+30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+1 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+1 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+2 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+2 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+3 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+3 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+4 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+4 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+5 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+5 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+6 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+6 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+7 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+7 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+8 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+8 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+9 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+9 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+10 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+10 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+11 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+11 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+12 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+12 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+13 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+13 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+14 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+14 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+15 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+15 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+16 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+16 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+17 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+17 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+18 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+18 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+19 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+19 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+20 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+20 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+21 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+21 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+22 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+22 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+23 hours";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));
$str="+23 hours +30 min";
$time_lists[] = date('H:i', strtotime($dateTime1.$str));



?>


<!-- html構成 -->
<?php
$title = 'tutor-reserve!|schedule';
$is_home =  false; //トップページの判定用の変数
include 'inc/head.php'; // head.php の読み込み
?>

<body>

<div class="wrapper">
<?php include("inc/header.php"); ?>

<div class="container mt-5">
  
  <?php include("inc/hello.php"); ?>

  <div class="row">
    <!-- 左カラム -->
    <div class="col-md-3">
      <?php include("inc/side_menu.php"); ?>
    </div>

    <!-- 右カラム -->
    <div class="col-md-9">
      <h5>■予約</h5>
      <p>ー はチューターの空き情報がありません</p>
      <p>◎ をクリックすることで予約フォームへ移動</p>
      

      <div id="alert" style="color:red; font-weight:bold;"></div>

      <table class="table table-striped table-bordered" id="targetTable">
        <thead>
          <tr>
            <th scope="col"></th>
            <?php echo $view; ?>
          </tr>
        </thead>
        <tbody>
        <?php $abc = array('a','b','c','d','e','f','g'); ?>
        <?php $today = date("Y-m-d H:i"); ?>

        <?php foreach ($time_lists as $index => $v): ?>
          <tr>
            <th scope="row"><?= $v; ?></th>
          
            <?php foreach ($days as $i => $day): ?>

                <?php $date_time1 = new DateTime($today); ?>
                <?php $date_time2 = new DateTime($day.$v); ?>
                
                <?php if ($date_time2 <= $date_time1): ?>
                  <td data-value="<?= $v; ?>&<?= $day; ?>" id="<?= $abc[$i]; ?><?= $index; ?>" class="desabled" >
                  <span><img src="./image/batu.png" width="15px"></span></td>

                <?php elseif ($date_time2 >= $date_time1): ?>
                
                  <?php $t = $day." ".$v.":00"; ?> 

                  <?php $flg = '<td data-value="'.$v.'&'.$day.'" id="'.$abc[$i].$index.'" class="'; ?>
                  <?php $flg .= 'none"'; ?>
                  <?php $flg .= '> <a href="javascript:void(0)"><img src="./image/dash.png" width="15px"></a></td>'; ?>

                  <?php foreach ($tutor_schedules as $key => $value): ?>
                    <?php $date_time3 = new DateTime($t); ?>
                    <?php $date_time4 = new DateTime($value["free"]); ?>
                    
                    <?php if ($date_time3 == $date_time4):?>
                        <?php $flg = '<td data-value="'.$v.'&'.$day.'" id="'.$abc[$i].$index.'" class="'; ?>
                        <?php $flg .= 'reserve" data-schedule='; ?>
                        <?php $flg .= $value["tutor_schedule_id"]; ?>
                        <?php $flg .= '><a href="user_schedule_insert.php?time='.$v.'&day='.$day.'"><img src="./image/maru.png" width="15px"></a></td>'; ?>
                        <?php break; ?>
                    <?php else:?>
                      <?php $flg = '<td data-value="'.$v.'&'.$day.'" id="'.$abc[$i].$index.'" class="'; ?>
                      <?php $flg .= 'none" data-schedule='; ?>
                      <?php $flg .= $value["tutor_schedule_id"]; ?>
                      <?php $flg .= '><span><img src="./image/dash.png" width="15px"></span></td>'; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>

                  <?= $flg; ?>
                  
                <?php else: ?>
                  <td data-value="<?= $v; ?>&<?= $day; ?>" id="<?= $abc[$i]; ?><?= $index; ?>" class="none" ><a href="javascript:void(0)"><img src="./image/dash.png" width="15px"></a></td>
                  
                <?php endif; ?>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
     
    </div>
  </div>
</div>

</div>
<?php include 'inc/footer.php'; ?>

</body>
</html>
