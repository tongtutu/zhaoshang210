<?php
use yii\helpers\Html;
?>

<div class="table-responsive no-padding">
  <table class="table table-bordered table-striped table-hover">
    <caption class="text-success">
    <span class="break-word"><kbd> <?php echo Html::encode($command) ?></kbd></span>
    </caption>
    <thead>
      <tr>
        <?php foreach ((array) $fields as $field): ?>
        <th> <?php echo $field ?> </th>
        <?php endforeach?>
      </tr>
    </thead>
    <?php foreach ((array) $datalist as $row): ?>
    <tr>
      <?php foreach ((array) $fields as $field): ?>
      <td><?php echo Html::encode($row[$field]) ?></td>
      <?php endforeach?>
    </tr>
    <?php endforeach?>
  </table>
</div>
