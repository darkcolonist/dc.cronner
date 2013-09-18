<h3>New Execution</h3>
<?= \Fuel\Core\Form::open("list/new") ?>
  <?= \Fuel\Core\Form::fieldset_open() ?>
    <?= \Fuel\Core\Form::label("Name", "name") ?>
    <?= \Fuel\Core\Form::input("name") ?>
    <?= \Fuel\Core\Form::submit("submit", "&raquo;") ?>
  <?= \Fuel\Core\Form::fieldset_close() ?>
<?= \Fuel\Core\Form::close() ?>
<? if($groups == null || count($groups) == 0): echo "no groups specified." ?>
<? else: ?>
  <h3>Execution List</h3>
  <? $error_message = Fuel\Core\Session::get_flash("error_message") ?>
  <? if(!empty($error_message)): ?>
    <p class="alert alert-error"><strong>error!</strong>&nbsp;<?= $error_message ?></p>
  <? endif ?>
  <? $success_message = Fuel\Core\Session::get_flash("success_message") ?>
  <? if(!empty($success_message)): ?>
    <p class="alert alert-success"><strong>success!</strong>&nbsp;<?= $success_message ?></p>
  <? endif ?>
  <ol>
    <? foreach($groups as $group): ?>
      <li><?= Fuel\Core\Html::anchor("terminal/execute/{$group->name}", $group->name) ?>
        (<?= Fuel\Core\Html::anchor("list/delete/{$group->name}", "x", array("title" => "delete", "class" => "button delete")) ?>)
      </li>
    <? endforeach; ?>
  </ol>
<? endif ?>
