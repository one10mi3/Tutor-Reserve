<?php 
$url = str_replace('/tutor-reserve/', '', $_SERVER['REQUEST_URI']);
// echo $url; 
?>

<h5>■受講生用メニュー</h5>
<div class="list-group">
  <a href="mypage.php" class="list-group-item list-group-item-action <?= "mypage.php" == $url ? "active" : '' ;?>" aria-current="true">マイページ</a>
  <a href="schedule.php" class="list-group-item list-group-item-action <?= "schedule.php" == $url ? "active" : '' ;?>">予約</a>
  <a href="schedule_history.php" class="list-group-item list-group-item-action <?= "schedule_history.php" == $url ? "active" : '' ;?>">予約履歴一覧</a>
  <a href="user_message.php" class="list-group-item list-group-item-action <?= "user_message.php" == $url ? "active" : '' ?>">チューターからのメッセージ</a>
  <a href="user_show.php" class="list-group-item list-group-item-action <?= "user_show.php" == $url ? "active" : '' ;?>">登録情報</a>
</div>

<?php  if ( $val["kanri_flg"] != 0 ): ?>
  <h5 class="mt-3">■チューター用メニュー</h5>
  <div class="list-group">
    <a href="tutor_schedule.php" class="list-group-item list-group-item-action <?= "tutor_schedule.php" == $url ? "active" : '' ;?> ">空き時間の登録</a>

    <a href="tutor_message.php" class="list-group-item list-group-item-action  <?= "tutor_message.php" == $url ? "active" : '' ;?> ">受講生からのメッセージ</a>
  </div>
<?php endif; ?>

<?php  if ( $val["kanri_flg"] == 3 ): ?>
  <h5 class="mt-3">■管理者用メニュー</h5>
  <div class="list-group">
    <a href="admin_user_list.php" class="list-group-item list-group-item-action <?= "admin_user_list.php" == $url ? "active" : '' ;?> ">ユーザー一覧</a>
    <a href="admin_skill_list.php" class="list-group-item list-group-item-action <?= "admin_skill_list.php" == $url ? "active" : '' ;?> ">スキル一覧</a>
    <a href="admin_period_list.php" class="list-group-item list-group-item-action <?= "admin_period_list.php" == $url ? "active" : '' ;?> ">期生一覧</a>
  </div>
<?php endif; ?>
