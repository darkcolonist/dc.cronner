<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=$title?> - Test Mode: <?=$module?></title>
  <?= isset($css) ? $css : null ?>
</head>
<body>

  <div class="ui-layout-north no-padding">
    <div id="header"><div id="logo"></div></div>
  </div>

  <div class="ui-layout-west">
    <?= Fuel\Core\Form::hidden("baseurl", \Fuel\Core\Uri::base()) ?>
    <?= Fuel\Core\Form::open(array(
        "action" => "api/cronnables/add.json",
        "class" => "singleline",
        "id" => "frmAddNewCronnable"
    )) ?>
    <?= Fuel\Core\Form::fieldset_open() ?>
    <?= Fuel\Core\Form::hidden("group_name", $group->name) ?>
    <?= Fuel\Core\Form::input("url", null, array(
        "placeholder" => "type url to add",
        "class" => "span-8"
    )) ?>
    <?= Fuel\Core\Form::input("interval", 60, array(
        "placeholder" => "specify interval",
        "class" => "span-2"
    )) ?>
    <?= Fuel\Core\Form::submit("submit", "Submit",
            array("class" => "hidden")) ?>
    <?= Fuel\Core\Form::fieldset_close() ?>
    <?= Fuel\Core\Form::close() ?>
    <i class="icon-exclamation-sign" id="error-icon"></i>
    <table id="cronnables" class="data">
      <tr class="empty"><td>please wait while we load your cronnables...</td></tr>
    </table>

    <div id="cronnable-toolbar">
      <a href="#mute" title="mute (toggle)" class="mute"><i class="icon-ban-circle"></i></a>
      <a href="#delete" title="delete" class="delete"><i class="icon-trash"></i></a>
    </div>
  </div>

  <div class="ui-layout-center">
    <div id="output"></div>
  </div>

  <div class="ui-layout-south no-padding">
    <div id="footer"><?= Fuel\Core\Html::anchor("list", "&laquo; back to list") ?> | &copy; darkcolonist 2013</div>
  </div>

  <?= isset($js) ? $js : null ?>
  <?= isset($js_scripts) ? "<script type='text/javascript'>".$js_scripts."</script>" : null ?>

  <script type="text/javascript">
    
  </script>

  <script type="text/javascript">
    Package.i_serverTime = "<?= date("Y-m-d H:i:s") ?>";
    Package.i_baseurl = "<?= \Fuel\Core\Uri::base() ?>";
    Package.i_groupData = <?= json_encode($group->toFormattedArray()) ?>;
    Package.i_layout_options = <?= json_encode(array(
        "defaults" => array(
        "resizable" => false,
        "closable" => false,
      ), "north" => array(
        "size" => 75
      ), "west" => array(
        "resizable" => true,
        "size" => 400,
        "closable" => true
      ), "east" => array(
        "size" => 150,
        "closable" => true
      ), "south" => array(
        "size" => 50
      ), "center" => array(
        "size" => 150
      ), "stateManagement" => array(
        "enabled" => true,
        "includeChildren" => false,
        "stateKeys" => "west.isClosed,east.isClosed"
      )
    )) ?>;
    
    Package.init();
  </script>
</body>
</html>