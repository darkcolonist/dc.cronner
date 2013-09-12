<? if($groups == null || count($groups) == 0): echo "no groups specified." ?>
<? else: ?>
  <h3>Execution List</h3>
  <ol>
    <? foreach($groups as $group): ?>
      <li><?= Fuel\Core\Html::anchor("terminal/execute/{$group->name}", $group->name) ?></li>
    <? endforeach; ?>
  </ol>
<? endif ?>
