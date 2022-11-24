<h3>こんにちは、<?= $val["last_name"].$val["first_name"] ?>さん 
  <?php if ($val["kanri_flg"]==0): ?>
    (受講生)
  <?php elseif ($val["kanri_flg"]==1): ?>
    (チューター)
  <?php elseif ($val["kanri_flg"]==2): ?>
    (サブチューター)
  <?php else: ?>
    (管理者)
  <?php endif; ?>
</h3>
