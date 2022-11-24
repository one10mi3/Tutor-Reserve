<?php
ini_set('display_errors',1);
//SESSION開始
session_start();
include("function.php");
sschk();

$sid = $_SESSION["user_id"];
$val = user_check($sid);
if ($val["kanri_flg"] == 0) {
  redirect("login.php");
}
if ($val["delete_flg"] == 0) {
  redirect("login.php");
}

$pdo = db_conn();
// スキルを登録しているか確認
$sql = <<<EOF
SELECT `tr_skills`.`skills`,`tr_skills`.`comment` 
FROM `tr_skills`
WHERE `tr_skills`.`user_id`= ? AND `tr_skills`.`delete_flg` = 1
EOF;
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $val["user_id"], PDO::PARAM_INT);
$status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else{
  $in_skill = $stmt->fetch();
  // echo $in_skill;
  // pre_print($in_skill);
  // echo  $schedules[0]["free_time"];
}
// if($in_skill){
//   echo "ある";
// } else {
//   echo "ない";
// }


// 登録済みのスケジュール情報取得
$sql = <<<EOF
SELECT `tutor_schedule_id`, `tutor_user_id`, `user_id`, `free`, `free_day`, `free_time`, `update_time`  
FROM `tr_tutor_schedules`
WHERE `tutor_user_id`= ? AND `free` >= CURRENT_TIMESTAMP
EOF;
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $val["user_id"], PDO::PARAM_INT);
$status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else{
  $schedules = $stmt->fetchAll();
  // pre_print($schedules);
  // echo  $schedules[0]["free_time"];
}

// 1.週の構成
// echo date('Y年m月d日 H時i分s秒'); // 現在の時間
$days = array();
$weeks = array();
// $start_day = start_day();
// $end_day = end_day();
$day = date('w');
// 今日の曜日を取得する
// $start_week = week_day_custom($day);
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
// echo date( "Y年m月d日 H時i分s秒" ) ;
// G	24時間単位の時。先頭に0を付けない。
$time_lists = array();
// echo date('i');
// if (date('i') >= '0' && date('i') <= '30') {
//   $time_lists[] = date('G').":00";
// } elseif ( date('i') >= '31' && date('i') <= '59') {
//   $time_lists[] = date('G').":30";
// } 
// $dateTime1 = '2019-06-19 12:50:30';

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

// [-]の雛形html
// $temp = '<td data-value="'.$v.'"&"'.$day.'" id="'.$abc[$i].$index.'" class="add" >
// <a href="javascript:void(0)"><img src="./image/dash.png" width="15px"></a></td>';

?>


<!-- html構成 -->
<?php
$title = 'tutor-reserve!|tutor schedule';
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
      <h5>■空き時間の登録</h5>

      <?php if($in_skill): ?>
      <p>ー をクリックすることで空き時間の登録（slackへ通知）</p>
      <p>◎ をクリックすることで空き時間の削除</p>

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
                  <?php $flg .= 'add"'; ?>
                  <?php $flg .= '> <a href="javascript:void(0)"><img src="./image/dash.png" width="15px"></a></td>'; ?>

                  <?php foreach ($schedules as $key => $value): ?>
                    <?php $date_time3 = new DateTime($t); ?>
                    <?php $date_time4 = new DateTime($value["free"]); ?>
                    <?php if ($date_time3 == $date_time4):?>
                      <?php $flg = '<td data-value="'.$v.'&'.$day.'" id="'.$abc[$i].$index.'" class="'; ?>
                      <?php $flg .= 'delete" data-schedule='; ?>
                      <?php $flg .= $value["tutor_schedule_id"]; ?>
                      <?php $flg .= '><a href="javascript:void(0)"><img src="./image/maru.png" width="15px"></a></td>'; ?>
                      <?php break; ?>
                    <?php endif; ?>
                  <?php endforeach; ?>

                  <?= $flg; ?>
                  
                <?php else: ?>
                  <td data-value="<?= $v; ?>&<?= $day; ?>" id="<?= $abc[$i]; ?><?= $index; ?>" class="add" ><a href="javascript:void(0)"><img src="./image/dash.png" width="15px"></a></td>
                  
                <?php endif; ?>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

      <?php else: ?>
      <p>スキル情報が未登録です</p>
      <p>登録したい場合は<a href="user_show.php">こちら</a></p>
      <?php endif; ?>
     
    </div>
  </div>
</div>

</div>
<?php include 'inc/footer.php'; ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript">
//add登録ボタンをクリック
$(document).on('click', '.add', function(e){
  // console.log(e.currentTarget.id); // イベントの起きたID取得
  let event_id = e.currentTarget.id;
  let element = document.getElementById(e.currentTarget.id);
  let getReq = element.dataset.value.split('&');
  // console.log(getReq[0]); //時間 free_time
  // console.log(getReq[1]); //日付

    //axiosでAjax送信
    //Ajax（非同期通信）
    const params = new URLSearchParams();
   
    params.append('free_time', getReq[0]);
    params.append('free_day', getReq[1]);
    // debugger;

    axios.post('tutor_schedule_insert_act.php', params).then(function (response) {
        // console.log(typeof response.data);//通信OK
        // console.log(response.data);
        if (response.data == "OK") {
          // console.log("aaaaaaa"+event_id) ;
        } else if (response.data) {
          // >>>>通信でデータを受信したら処理をする場所<<<<
          let res = response.data.split('&');
          // console.log(res[0]);
          // console.log(res[1]);
          document.querySelector("#alert").innerHTML=res[0];
          document.getElementById(event_id).innerHTML='<a href="javascript:void(0)"><img src="./image/maru.png" width="15px"></a>';
          document.getElementById(event_id).classList.remove('add');
          document.getElementById(event_id).classList.add('delete');

          let el = document.getElementById(event_id);
          el.dataset.schedule = res[1];
        } 
    }).catch(function (error) {
        console.log(error);//通信Error
    }).then(function () {
        // console.log("Last");//通信OK/Error後に処理を必ずさせたい場合
    });
});

//delete削除ボタンをクリック
$(document).on('click', '.delete', function(e){
  // console.log(e.currentTarget.id); // イベントの起きたID取得
  let event_id = e.currentTarget.id;
  let element = document.getElementById(e.currentTarget.id);
  // let getReq = element.dataset.value.split('&');
  let getSchedule = element.dataset.schedule;
  // console.log(getReq[0]); //時間 free_time
  // console.log(getReq[1]); //日付

    //axiosでAjax送信
    //Ajax（非同期通信）
    const params = new URLSearchParams();
   
    // params.append('free_time', getReq[0]);
    // params.append('free_day', getReq[1]);
    params.append('schedule_id', getSchedule);

    axios.post('tutor_schedule_delete_act.php', params).then(function (response) {
        // console.log(typeof response.data);//通信OK
        // console.log(response.data);
        if (response.data == "OK") {
          // console.log("aaaaaaa"+event_id) ;
        } else if (response.data) {
          // >>>>通信でデータを受信したら処理をする場
          document.querySelector("#alert").innerHTML=response.data;
          document.getElementById(event_id).innerHTML='<a href="javascript:void(0)"><img src="./image/dash.png" width="15px"></a>';
          document.getElementById(event_id).classList.remove('delete');
          document.getElementById(event_id).classList.add('add');
          let el = document.getElementById(event_id);
          delete el.dataset.schedule;
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
